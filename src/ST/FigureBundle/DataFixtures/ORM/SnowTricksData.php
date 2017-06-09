<?php
namespace ST\FigureBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class SnowTricksData extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder()
	{
		return 0;
	}

	public function load(ObjectManager $manager)
	{
		$conn=$manager->getConnection();
		$file=__DIR__ . '/data.sql';
		if(!file_exists($file))
		{
			echo sprintf('File %s does not exists', $file);
			return;
		}
		$data = file_get_contents($file);
 
		$conn->executeUpdate($data);
	}
}
