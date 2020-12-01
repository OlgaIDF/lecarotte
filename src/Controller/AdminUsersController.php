<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin/adminUsers.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/create", name="user_create")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Le nouvel utilisateur à bien été ajouté'
                );
            }
            else{
                $this->addFlash(
                    'danger',
                    'Une erreur est survenue'
                );
            }
            return $this->redirectToRoute('admin_users');
        };
        return $this->render('admin/adminUserForm.html.twig', [
            'formulaireUser' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/update-{id}", name="user_update")
     */
    public function updateUser(UserRepository $userRepository, $id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Le nouvel utilisateur à bien été modifié'
                );
            }
            else{
                $this->addFlash(
                    'danger',
                    'Une erreur est survenue'
                );
            }
            return $this->redirectToRoute('admin_users');
        };
        return $this->render('admin/adminUserForm.html.twig', [
            'formulaireUser' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/delete-{id}", name="user_delete")
     */
    public function deleteUser(UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le nouvel utilisateur a bien été supprimé'
        );

        return $this->redirectToRoute('admin_users');
    }
}