<?php

namespace App\Controller;

use App\Entity\PostLike;
use App\Entity\Project;
use App\Entity\Users;
use App\Repository\PostLikeRepository;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 * @package App\Controller
 */
class ProjectController extends AbstractController
{
    /**
     * @param PaginatorInterface $paginator
     * @param ProjectRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/projects", name="projects")
     */
    public function project(PaginatorInterface $paginator, ProjectRepository $repository, Request $request) {
        $projects = $paginator->paginate($repository->findBy(['status' => '1']),
            $request->query->getInt('page', 1),
            4);

        return $this->render('project/projects.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @param Project $projects
     * @return Response
     * @Route("/project/{id}", name="project")
     */
    public function projectShow(Project $projects) {
        return $this->render('project/project-show.html.twig', [
            'project' => $projects
        ]);
    }

    /**
     * @Route("/project/{id}/like", name="postlike")
     * @param Project $project
     * @param ObjectManager $manager
     * @param PostLikeRepository $likeRepository
     * @return Response
     */
    public function like(Project $project, ObjectManager $manager, PostLikeRepository $likeRepository): Response {
        $user = $this->getUser();

        if(!$user) return $this->json([
           'code' => 403,
           'message' => "Pas connecté"
        ], 403);

        if ($project->isLikedByUser($user)) {
            $like = $likeRepository->findOneBy([
                'post' => $project,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $likeRepository->count(['post' => $project])
            ], 200);
        }

        $like = new PostLike();
        $like->setPost($project)
             ->setUser($user);
        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'OK',
            'likes' => $likeRepository->count(['post' => $project])
        ], 200);
    }
}
