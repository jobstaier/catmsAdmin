<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use CatMS\AdminBundle\Logger\History;

/**
 * MediaLibrary controller.
 *
 * @Route("/ajax")
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
     * @Route("/get-groups", name="ajax-get-groups")
     */
    public function getGroupsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $groups = $em->getRepository('CatMSAdminBundle:ImageGroup')->findBy(array(), array('description' => 'asc'));
        
        $groupsArr = array();
        foreach ($groups as $key => $obj) {
            $groupsArr[$obj->getSlug()] = $obj->getDescription();
        }

        $groupsJson = json_encode($groupsArr);
        return new Response($groupsJson, 200, array('Content-Type' => 'application/json'));
    }
    
    /**
     * Get content group list
     * 
     * @Route("/get-content-groups", name="ajax-get-content-groups")
     */
    public function ajaxGetContentGroupsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $groups = $em->getRepository('CatMSAdminBundle:ContentGroup')->findBy(array(), array('description' => 'asc'));
        
        $groupsArr = array();
        foreach ($groups as $key => $obj) {
            $groupsArr[$obj->getSlug()] = $obj->getDescription();
        }

        $groupsJson = json_encode($groupsArr);
        return new Response($groupsJson, 200, array('Content-Type' => 'application/json'));        
    }
    
    /**
     * @Route("/admin/get-related-image-groups/{group}", 
     *  name="ajax-get-related-image-groups",
     *  requirements={"group"="\d+"}
     * )
     */
    public function ajaxGetRelatedImageGroupAction(\CatMS\AdminBundle\Entity\ContentGroup $group)
    {
        $related = $group->getRelatedImages();

        $relatedArr = array();
        
        foreach($related as $imageGroup) {
            foreach($imageGroup->getImages() as $img) {
                $relatedArr[$imageGroup->getDescription()][$img->getId()]['path'] = $img->getPath();
                $relatedArr[$imageGroup->getDescription()][$img->getId()]['title'] = $img->getTitle();
            }
        }

        return new Response(json_encode($relatedArr), 200, array('Content-Type' => 'application/json'));        
    }
    
    /**
     * @Route("/admin/ajax-get-related-image-inject/{group}", 
     *  name="ajax-get-related-image-inject",
     *  requirements={"group"="\d+"}
     * )
     */
    public function ajaxGetRelatedImageInjectAction(\CatMS\AdminBundle\Entity\ContentGroup $group)
    {
        $pattern = "(#img=([0-9])+#)";
        
        foreach ($group->getContents() as $content) {
            $this->parseContent($pattern, $content->getFullText());
            $this->parseContent($pattern, $content->getShortText());
        }
        
        return new Response(json_encode($this->injectedArr), 200, array('Content-Type' => 'application/json'));        
    }
    
    private function parseContent($pattern, $content) 
    {
        $em = $this->getDoctrine()->getManager();
        $matches = array();
        $images = array();
        
        preg_match_all($pattern, $content, $matches);

        if ($matches) {
            foreach($matches[0] as $key => $code) {
                preg_match("([0-9]+)", $code, $idMatch);
                $id = $idMatch[0];

                $image = $em->getRepository('CatMSAdminBundle:ImageUpload')->find($id);

                if ($image) {                
                    $this->injectedArr[$image->getImageGroup()->getDescription()][$image->getId()]['path'] = $image->getPath();
                    $this->injectedArr[$image->getImageGroup()->getDescription()][$image->getId()]['title'] = $image->getTitle();
                }
            }
        } 
        return $images;
    }
    
    /**
     * @Route("/admin/ajax-get-history", 
     *  name="ajax-get-history"
     * )
     */
    public function getHistoryAction()
    {
        $history = new History($this->get('session'), $this->get('router'));
        return new Response(json_encode($history->getHistory()), 200, array('Content-Type' => 'application/json'));
    }
    
    
    /**
     * @Route("/admin/get-images-list",
     *  name="get-images-list-ajax"
     * )
     */
    public function getImagesListAjax()
    {
        $request = $this->getRequest();
        
        $page = $request->request->get('page');
        $recordsCount = 24;
        
        $repository = $this->getDoctrine()->getRepository('CatMSAdminBundle:ImageUpload');
        
        $query = $repository->createQueryBuilder('a')
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
                ->getQuery()
                ->getSingleScalarResult();
        
        $results['hasMore'] = ($page * $recordsCount < $count) ? true : false;
        $results['countAll'] = $count;
        
        return new Response(json_encode($results), 200, array('Content-Type' => 'application/json'));   
    }
    
}
?>
