<?php

namespace InterNations\UserGroupBundle\Controller;

use InterNations\UserGroupBundle\Entity\Users;
use InterNations\UserGroupBundle\Entity\Groups;
use InterNations\UserGroupBundle\Entity\UsersGroups;
use InterNations\UserGroupBundle\Entity\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Groups controller.
 *
 */
class GroupsApiController extends FOSRestController
{
	public function showAction(Groups $group)
    {
        return $group;
    }

    public function indexAction()
    {
        return $this->getDoctrine()->getRepository('UserGroupBundle:Groups')->findAll();
    }

    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $group = new Groups();
        $group->setName($data['name']);

        $em = $this->getDoctrine()->getManager();

        $em->persist($group);
        $em->flush();

        $view = $this->view($group, 201);
        return $this->handleView($view);
    }

    public function editAction(Groups $group, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $group->setName($data['name']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return $group;
    }

    public function deleteAction(Groups $group, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();

        $view = $this->routeRedirectView('groups_api_index', array(), 301);
        return $view;
    }

}
