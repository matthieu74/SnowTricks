<?php
namespace ST\FigureBundle\Models\Service;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class LayoutService
{
	public function  __construct()
	{
	}
	
	public function getPaging($tab, $nbPerPage, $offset)
	{
		$array = [];
		$array['oldestDisable'] = '';
        $array['newestDisable'] = '';
        if ($offset == 0)
        {
            $array['newestDisable'] = 'not-active';   
        }
        
        if (count($tab) < $nbPerPage) 
        {
            $array['oldestDisable'] = 'not-active';
        }
        $array['oldestOffset'] = $offset + 1;
        $array['newestOffset'] = $offset - 1;
		return $array;
	}
}