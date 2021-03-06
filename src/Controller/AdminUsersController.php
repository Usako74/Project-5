<?php

namespace App\Controller;

use App\Form\AdminUsersType;
use App\Repository\PostLikeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminUsersController
 * @package App\Controller
 */
class AdminUsersController extends AbstractController
{
    /**
     * @param UsersRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param Users|null $users
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin-users-list", name="admin-users-list")
     */
    public function AdminUsersList(UsersRepository $repository, PaginatorInterface $paginator, Request $request, Users $users = null) {
        $usersPages = $paginator->paginate($repository->findAll(),
            $request->query->getInt('page', 1),
            10);

        return $this->render('admin/admin-users-list.html.twig', [
            'users' => $usersPages,
            'editMode' => $repository->findAll() == null
        ]);

    }

    /**
     * @param Users|null $users
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin-users-list/{id}/edit", name="admin-users-list-edit")
     */
    public function AdminUsersListEdit(Users $users = null, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        if (!$users) {
            $users = new Users();
        }

        $formEdit = $this->createForm(AdminUsersType::class, $users);
        $formEdit-> handleRequest($request);
        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            if ($formEdit->get('password') == null) {
                $users->setPassword($users->getPassword());
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

    /**
     * @param Users $users
     * @param PostLikeRepository $likeRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("admin-user-list/{id}/delete", name="admin-user-delete")
     */
    public function adminDeleteUser(Users $users, PostLikeRepository $likeRepository, ObjectManager $manager) {
        $like = $likeRepository->findBy([
            'user' => $users
        ]);

        foreach ($like as $likes){
            $manager->remove($likes);
        }

        $manager->remove($users);
        $manager->flush();

        return $this->redirectToRoute('admin-users-list');
    }
}
