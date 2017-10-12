<?php

namespace InterNations\UserGroupBundle\Controller;

use InterNations\UserGroupBundle\Entity\Users;
use InterNations\UserGroupBundle\Entity\Groups;
use InterNations\UserGroupBundle\Entity\UsersGroups;
use InterNations\UserGroupBundle\Entity\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 */
class GroupsController extends Controller
{
    /**
     * Lists all group entities.
     * @return List view for entity Groups
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('UserGroupBundle:Groups')->findAll();

        // Fetch list of group Ids
        $groupIds = array_map(create_function('$fetchGroupIdsFromResult', 'return $fetchGroupIdsFromResult->getId();'), $groups);

        $usersGroupsQuery = $em->createQuery(
            'SELECT DISTINCT q.groupid from UserGroupBundle:UsersGroups q
             WHERE q.groupid IN (:groupids)'
        )->setParameter('groupids', $groupIds);

        $usersGroups = $usersGroupsQuery->getResult();

        $usersGroups = array_map(create_function('$fetchGroupIdsFromResult', 'return $fetchGroupIdsFromResult["groupid"];'), $usersGroups);

        return $this->render('@UserGroup/Groups/index.html.twig', array(
            'groups' => $groups,
            'usersGroups' => $usersGroups
        ));
    }

    /**
     * Creates a new group entity.
     * @return Form view for entity Groups
     */
    public function newAction(Request $request)
    {
        $group = new Groups();
        $form = $this->createForm('InterNations\UserGroupBundle\Form\GroupsType', $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('groups_index');
        }

        return $this->render('@UserGroup/Groups/new.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a group entity.
     * @param InterNations\UserGroupBundle\Entity\Groups $group
     * @return array of InterNations\UserGroupBundle\Form\GroupsType
     */
    public function showAction(Groups $group)
    {
        $deleteForm = $this->createDeleteForm($group);

        return $this->render('@UserGroup/Groups/show.html.twig', array(
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing group entity.
     * @param Symfony\Component\HttpFoundation $request
     * @param int $id
     * @return array of InterNations\UserGroupBundle\Form\GroupsType
     */
    public function editAction(Request $request, Groups $group)
    {
    	$em = $this->getDoctrine()->getManager();

    	// Fetch group id to search if any users are assigned
    	$id = $group->getId();

    	// Fetch if any users are assigned to group
        $groupsQuery = $em->createQuery(
            'SELECT q.userid FROM UserGroupBundle:UsersGroups q
             WHERE q.groupid = :id
            '
        )->setParameter('id', $id);
        
        $groups = $groupsQuery->getResult();

        $deleteForm = $this->createDeleteForm($group);
        $editForm = $this->createForm('InterNations\UserGroupBundle\Form\GroupsType', $group);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groups_index', array('id' => $group->getId()));
        }

        $returnArray = array(
        	'group' => $group,
            'edit_form' => $editForm->createView()
        );

        if (count($groups) === 0) {
        	$returnArray['delete_form'] = $deleteForm->createView();
        }

        return $this->render('@UserGroup/Groups/edit.html.twig', $returnArray);
    }

    /**
     * Deletes a group entity.
     * @param Symfony\Component\HttpFoundation $request
     * @param InterNations\UserGroupBundle\Entity\Groups $groups
     * @return redirect to route groups_index
     */
    public function deleteAction(Request $request, Groups $group)
    {
    	$em = $this->getDoctrine()->getManager();
    	
        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        // Fetch Group Id to prevent deletion if any users are assigned
        $id = $group->getId();

        // Lookup the UsersGroups table for any existing users
        $groupsQuery = $em->createQuery(
            'SELECT q.userid FROM UserGroupBundle:UsersGroups q
             WHERE q.groupid = :id
            '
        )->setParameter('id', $id);
        
        $groups = $groupsQuery->getResult();

        if (count($groups) > 0) {
        	// Raise error to the form
        	throw $this->createAccessDeniedException('You cannot access this page!');
        } else {
	        $em->remove($group);
	        $em->flush();
        }

        return $this->redirectToRoute('groups_index');
    }

    /**
     * Creates a form to delete a group entity.
     *
     * @param Groups $group The group entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groups $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groups_delete', array('id' => $group->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
