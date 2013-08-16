<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use CatMS\AdminBundle\Logger\History;
use CatMS\AdminBundle\Form\AssetProtoType;
use CatMS\AdminBundle\Entity\ContentGroup;

/**
 * MediaLibrary controller.
 *
 */
class AjaxController extends Controller
{
    private $injectedArr;
    
    public function __construct() {
        $this->injectedArr = array();
    }
    
    /**
     * Get image group list
     * 
     */
    public function getGroupsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $groups = $em->getRepository('CatMSAdminBundle:ImageGroup')
            ->findBy(array(), array('description' => 'asc'));
        
        $groupsArr = array();
        foreach ($groups as $key => $obj) {
            $groupsArr[$key]['slug'] = $obj->getSlug();
            $groupsArr[$key]['description'] = $obj->getDescription();
            $groupsArr[$key]['id'] = $obj->getId();
        }

        $groupsJson = json_encode($groupsArr);
        return new Response(
            $groupsJson, 
            200, 
            array('Content-Type' => 'application/json')
        );
    }
    
    /**
     * Get content group list
     * 
     */
    public function getContentGroupsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $groups = $em->getRepository('CatMSAdminBundle:ContentGroup')
            ->findBy(array(), array('description' => 'asc'));
        
        $groupsArr = array();
        foreach ($groups as $key => $obj) {
            $groupsArr[$obj->getSlug()] = $obj->getDescription();
        }

        $groupsJson = json_encode($groupsArr);
        return new Response(
            $groupsJson, 
            200, 
            array('Content-Type' => 'application/json')
        );        
    }
    
    /**
     * Get realted images
     * 
     */
    public function getRelatedImageGroupAction(ContentGroup $group)
    {
        $related = $group->getRelatedImages();

        $relatedArr = array();
        
        foreach($related as $imageGroup) {
            foreach($imageGroup->getImages() as $img) {
                $relatedArr[$imageGroup->getDescription()][$img->getId()]['path'] = 
                    $img->getPath();
                $relatedArr[$imageGroup->getDescription()][$img->getId()]['title'] = 
                    $img->getTitle();
            }
        }

        return new Response(
            json_encode($relatedArr), 
            200, 
            array('Content-Type' => 'application/json')
        );        
    }
    
    /**
     * Get history
     * 
     */
    public function getHistoryAction()
    {
        $history = new History($this->get('session'), $this->get('router'));
        return new Response(
            json_encode($history->getHistory()),
            200, 
            array('Content-Type' => 'application/json')
        );
    }
    
    
    /**
     * Get images list
     */
    public function getImagesListAction()
    {
        $request = $this->getRequest();
        
        $page = $request->query->get('page');
        $recordsCount = 24;
        
        $repository = $this->getDoctrine()
            ->getRepository('CatMSAdminBundle:ImageUpload');
        
        $query = $repository->createQueryBuilder('a')
            ->setFirstResult($page * $recordsCount - $recordsCount)
            ->setMaxResults($recordsCount)
            ->getQuery();

        $images = $query->getResult();
            
        $results = array();
        
        foreach ($images as $image) {
            $results['images'][] = $image->serialize() + array(
                'deletePath' => $this->generateUrl(
                    'media-library-delete-inline',
                    array('id' => $image->getId())
                )
            );
        }
        
        $count = $repository->createQueryBuilder('a')
                ->select('count(a.id)')
                ->getQuery()
                ->getSingleScalarResult();
        
        $results['hasMore'] = ($page * $recordsCount < $count) ? true : false;
        $results['countAll'] = $count;
        
        return new Response(json_encode($results), 200, 
            array('Content-Type' => 'application/json')
        );   
    }
    
    
    /**
     * Get group images list
     * 
     */
    public function getGroupImagesListAjaxAction($group)
    {
        $request = $this->getRequest();
        
        $page = $request->query->get('page');
        
        $recordsCount = 24;
        
        $repository = $this->getDoctrine()
            ->getRepository('CatMSAdminBundle:ImageUpload');
        
        $query = $repository->createQueryBuilder('a')
            ->where('a.imageGroup = :group')
            ->setParameter('group', $group)
            ->setFirstResult($page * $recordsCount - $recordsCount)
            ->setMaxResults($recordsCount)
            ->getQuery();

        $images = $query->getResult();
        $results = array();
        
        foreach ($images as $image) {
            $results['images'][] = $image->serialize();
        }
        
        $count = $repository->createQueryBuilder('a')
                ->select('count(a.id)')
                ->where('a.imageGroup = :group')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
        
        $results['hasMore'] = ($page * $recordsCount < $count) ? true : false;
        $results['countAll'] = $count;
        
        return new Response(json_encode($results), 200, 
            array('Content-Type' => 'application/json')
        );     
    }   
}
?>
