<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\AdminProjectType;
use App\Repository\PostLikeRepository;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProjectController
 * @package App\Controller
 */
class AdminProjectController extends AbstractController
{

    /**
     * @param ProjectRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin-project-list", name="admin-project-list")
     */
    public function adminListProjects(ProjectRepository $repository, PaginatorInterface $paginator, Request $request) {
        $projects = $paginator->paginate($repository->findAll(),
            $request->query->getInt('page', 1),
            4);
        return $this->render('admin/admin-project-list.html.twig', [
            'projects' => $projects
        ]);
    }


    /**
     * @param Project|null $project
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/admin-project", name="admin-project")
     * @Route("/admin-project/{id}/edit", name="admin-project-edit")
     */
    public function adminCreateProject(Project $project = null, Request $request, ObjectManager $manager) {
        if (!$project) {
            $project = new Project();
        }
        $form = $this->createForm(AdminProjectType::class, $project);
        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                throw new \Exception('Une erreur s\'est produite');
            }

            $project->setImage($fileName);
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render('admin/admin-project.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName() {
        return md5(uniqid());
    }


    /**
     * @param Project $projects
     * @param PostLikeRepository $likeRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("admin-project-list/{id}/delete", name="admin-project-delete")
     */
    public function adminDeleteProject(Project $projects, PostLikeRepository $likeRepository, ObjectManager $manager) {
        $user = $this->getUser();
        if ($projects->isLikedByUser($user)) {
            $like = $likeRepository->findOneBy([
                'post' => $projects,
                'user' => $user
            ]);
            $manager->remove($like);
        }

        $manager->remove($projects);
        $manager->flush();

        return $this->redirectToRoute('admin-project-list');
    }
}
