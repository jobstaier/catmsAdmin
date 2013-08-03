<?php
    /*
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
    */
?>
