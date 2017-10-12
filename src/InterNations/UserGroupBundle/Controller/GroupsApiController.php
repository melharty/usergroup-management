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
 * Groups API controller.
 *
 */
class GroupsApiController extends FOSRestController
{
    /**
     * Finds and displays a group entity.
     * @param InterNations\UserGroupBundle\Entity\Groups $group
     * @return InterNations\UserGroupBundle\Entity\Groups
     */
    public function showAction(Groups $group)
    {
        // Return the entity and FOS-Rest will handle encoding to JSON
        return $group;
    }

    /**
     * Lists all group entities.
     * @return List view for entity Groups
     */
    public function indexAction()
    {
        // Pull a repository of all Groups and return
        // FOS Rest Bundle will handle the conversion
        return $this->getDoctrine()->getRepository('UserGroupBundle:Groups')->findAll();
    }

    /**
     * Creates a new group entity.
     * @param Symfony\Component\HttpFoundation $request
     * @return InterNations\UserGroupBundle\Entity\Groups
     */
    public function newAction(Request $request)
    {
        // Pull and decode data from POST
        // TODO: Not the best way to use native json_decode
        // Preferable to use a serializer either the one from
        // Symfony or JMS Serializer Bundle imported by FOS-Rest
        $data = json_decode($request->getContent(), true);

        // Create a new Group Entity to save the data from POST
        $group = new Groups();

        // Set the data
        $group->setName($data['name']);

        // Create an object of Doctrine Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Persist & flush (Save & Commit)
        $em->persist($group);
        $em->flush();

        // Respond with HTTP Status code 201 (CREATED)
        $view = $this->view($group, 201);
        return $this->handleView($view);
    }

    /**
     * Displays a form to edit an existing group entity.
     * @param Symfony\Component\HttpFoundation $request
     * @param int $id
     * @return InterNations\UserGroupBundle\Entity\Groups
     */
    public function editAction(Groups $group, Request $request)
    {
        // Pull and decode data from POST
        // TODO: Not the best way to use native json_decode
        // Preferable to use a serializer either the one from
        // Symfony or JMS Serializer Bundle imported by FOS-Rest
        $data = json_decode($request->getContent(), true);

        // Update the entity with the data from POST
        $group->setName($data['name']);

        // Create an object of Doctrine Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Persist & flush (Save & Commit)
        $em->persist($group);
        $em->flush();

        // Return the entity and FOS-Rest will handle encoding to JSON
        return $group;
    }

    /**
     * Deletes a group entity.
     * @param InterNations\UserGroupBundle\Entity\Groups $group
     * @param Symfony\Component\HttpFoundation $request
     * @return redirect to route groups_api_index
     */
    public function deleteAction(Groups $group, Request $request)
    {
        // Create an object of Doctrine Entity Manager3
        $em = $this->getDoctrine()->getManager();

        // Fetch Group Id to prevent deletion if any users are assigned
        $id = $group->getId();

        // Lookup the UsersGroups table for any existing users
        $groupsQuery = $em->createQuery(
            'SELECT q.userid FROM UserGroupBundle:UsersGroups q
             WHERE q.groupid = :id
            '
        )->setParameter('id', $id);

        $groups = $groupsQuery->getResult();

        // Check if the group has any users assigned to it
        if (count($groups) > 0) {
            // Respond with HTTP Status code 403 (FORBIDDEN)
            // TODO: Might not be the best error code to use, need to look up alternatives
            $view = $this->view('Cannot delete group with existing members', 403);
        } else { // Group has no users assigned
            // Remove & flush (Remove & Commit)
            $em->remove($group);
            $em->flush();

            // Set the redirect view to groups_api_index after successful deletion
            $view = $this->routeRedirectView('groups_api_index', array(), 301);
        }

        return $view;
    }

}
