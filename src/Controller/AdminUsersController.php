<?php

namespace App\Controller;

use App\Form\AdminUsersType;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\UserSearchType;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;

class AdminUsersController extends AbstractController
{

    /**
     * @Route("/admin-users-list", name="admin-users-list")
     */
    public function AdminUsersList(UsersRepository $repository, PaginatorInterface $paginator, Request $request, Users $users = null)
    {
        $form = $this->createForm(UserSearchType::class, $users);
        $form-> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$users = null) {
                $repository->findBy(['email' => 'test@test.fr']);
                return $this->render('admin/admin-users-list.html.twig', [
                    'users' => $repository,
                    'form' => $form->createView()
                ]);
            }
        }

        $usersPages = $paginator->paginate($repository->findAll(),
            $request->query->getInt('page', 1),
            10);

        return $this->render('admin/admin-users-list.html.twig', [
            'users' => $usersPages,
            'form' => $form->createView(),
            'editMode' => $repository->findAll() == null
        ]);

    }

    /**
     * @Route("/admin-users-list/{id}/edit", name="admin-users-list-edit")
     */
    public function AdminUsersListEdit(Users $users = null, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        if (!$users) {
            $users = new Users();
        }

        $formEdit = $this->createForm(AdminUsersType::class, $users);
        $formEdit-> handleRequest($request);
        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            if ($formEdit->get('password') == null) {
                $users->setPassword($users->getPassword());
                dump($users);
            } else {
                $hash = $encoder->encodePassword($users, $users->getPassword());
                $users->setPassword($hash);
            }
            $role = $formEdit->get('roles')->getData();
            $users->setRoles($role);
            $manager->persist($users);
            $manager->flush();
        }

        return $this->render('admin/admin-users-list.html.twig', [
            'formEdit' => $formEdit->createView(),
            'users' => $users,
            'editMode' => $users->getId() !== null
        ]);
    }
}
