<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class APIController extends AbstractController
{
    #[Route('/user', name: 'get_all_user', methods: ['GET'])]
    public function getAll(UserRepository $userRepository): Response
    {
        // On récupère les infos de la bdd
        $users = $userRepository->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = $user;
        }
        
        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/user/{user}', name: 'get_user', methods: ['GET'])]
    public function get($user, UserRepository $userRepository): Response
    {
        //On récupère les infos des users par id dans l'url
        $user = $userRepository->findOneBy(['idUser' => $user]);

        if ($user === null) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/user/add', name: 'add_user', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        // On décode les informations envoyés dans l'url
        $data = json_decode($request->getContent(), true);

        // if (empty($data['titre']) || empty($data['auteur']) || empty($data['nbPage'])) {
        //     return $this->json(['message' => 'Tous les champs doivent être renseignés'], Response::HTTP_BAD_REQUEST);
        // }

        // On prépare la requête a envoyer
        $lastname = $data['lastname'];
        $firstname = $data['firstname'];
        $mail = $data['mail'];
        $password = $data['password'];
        $phone = $data['phone'];
        $cv = $data['cv'];
        $area = $data['area'];
        $address = $data['address'];
        $role = $data['role'];
        $admin = false;

        //On instancie le nouvel utilisateur
        $newUser = new User();
        $newUser->setLastname($lastname)->setFirstname($firstname)->setMail($mail)->setPassword($password)->setPhone($phone)->setCv($cv)
        ->setArea($area)->setAddress($address)->setRole($role)->setAdmin($admin);

        //On envoie les infos
        $em->persist($newUser);
        $em->flush();

        return $this->json(['message' => 'User créé'], Response::HTTP_CREATED);
    }

    #[Route('/user/update/{user}', name: 'put_patch_user', methods: ['PUT','PATCH'])]
public function putPatch(Request $request, $user, UserRepository $userRepository, EntityManagerInterface $em): Response
{
    // On décode les informations envoyés dans l'url
    $data = json_decode($request->getContent(), true);

    //On récupère les infos des users par id dans l'url
    $userObject = $userRepository->findOneBy(['idUser'=>$user]);

    if($userObject===null){
        throw $this->createNotFoundException(sprintf(
            'Pas de user trouvé "%s"',
            $user
        ));
    }

    // if($request->getMethod()==='PUT' and (empty($data['titre']) || empty($data['auteur']) || empty($data['nbPage']))){
    //     return $this->json(['message'=>'Tous les champs doivent être renseignés'],Response::HTTP_BAD_REQUEST);
    // }

    //On récupère les données de l'utilisateur selectioné et on les remplaces.
    if(!empty($data['lastname'])) $userObject->setLastname($data['lastname']);
    if(!empty($data['firstname'])) $userObject->setFirstname($data['firstname']);
    if(!empty($data['mail'])) $userObject->setMail($data['mail']);
    if(!empty($data['password'])) $userObject->setPassword($data['password']);
    if(!empty($data['phone'])) $userObject->setPhone($data['phone']);
    if(!empty($data['cv'])) $userObject->setCv($data['cv']);
    if(!empty($data['area'])) $userObject->setArea($data['area']);
    if(!empty($data['address'])) $userObject->setAddress($data['address']);
    if(!empty($data['role'])) $userObject->setRole($data['role']);
    if(!empty($data['admin'])) $userObject->setAdmin($data['false']);
    $em->flush();

    return $this->json($userObject,Response::HTTP_OK);
}

#[Route('/user/delete/{user}', name: 'delete_user', methods: ['DELETE'])]
public function delete($user, UserRepository $userRepository, EntityManagerInterface $em): Response
{
    //On récupère l'utilisateur par son id 
    $userObject = $userRepository->findOneBy(['idUser'=>$user]);

    if($userObject===null){
        throw $this->createNotFoundException(sprintf(
            'Pas de user trouvé "%s"',
            $user
        ));
    }

    $em->remove($userObject);
    $em->flush();

    return $this->json(null,Response::HTTP_NO_CONTENT);
}
}
