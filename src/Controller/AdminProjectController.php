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

class AdminProjectController extends AbstractController
{
    /**
     * @Route("/admin-project-list", name="admin-project-list")
     */
    public function adminListProjects(ProjectRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $projects = $paginator->paginate($repository->findAll(),
            $request->query->getInt('page', 1),
            4);
        return $this->render('admin/admin-project-list.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/admin-project", name="admin-project")
     * @Route("/admin-project/{id}/edit", name="admin-project-edit")
     */
    public function adminCreateProject(Project $project = null, Request $request, ObjectManager $manager)
    {
        if (!$project) {
            $project = new Project();
        }
        $form = $this->createForm(AdminProjectType::class, $project);
        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @Route("admin-project-list/{id}/delete", name="admin-project-delete")
     */
    public function adminDeleteProject(Project $projects, PostLikeRepository $likeRepository, ObjectManager $manager)
    {
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
