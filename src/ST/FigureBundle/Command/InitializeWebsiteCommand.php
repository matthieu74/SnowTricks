<?php

namespace ST\FigureBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use ST\UserBundle\Entity\User;
use ST\FigureBundle\Entity\TypeFigure;
use ST\FigureBundle\Entity\Figure;
use ST\FigureBundle\Entity\Comment;
class InitializeWebsiteCommand extends Command
{
    protected function configure()
    {
       $this
			->setName('snowtricks:init-website')
			->setDescription('initialize the website')
		    ->addOption('append', null,  InputOption::VALUE_NONE, 'Append the data instead of deleteing all data form the database first.')
		  	->setHelp('This command fills the database with figures, comments and users...')
		   ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$format = 'Y-m-d H:i:s';
		if (!$input->getOption('append'))
		{
			$helper = $this->getHelper('question');
			$question = new ConfirmationQuestion('Database will be purged. do you want to continue y/N', false);

			if (!$helper->ask($input, $output, $question)) {
				return;
			}
			
		}
		$file=__DIR__ . '/data.yml';
		$yml = Yaml::parse(file_get_contents($file));
    	
		$users = array();
		$figures = array();
		$typeFigureByFigures = array();
		$comments = array();
		foreach($yml as $key => $value)
		{
			$ymlKey = 'user';
			if (substr($key, 0, strlen($ymlKey)) == $ymlKey)
			{
				$userToCreate = new User();
				foreach($value as $keyUser => $valueUser)
				{

					$fct = 'set'.ucfirst($keyUser);
					if ($keyUser == 'password')
					{
						$val = password_hash($valueUser, PASSWORD_BCRYPT);
						call_user_func_array(array($userToCreate, $fct),array($val));
					}
					else
					{
						call_user_func_array(array($userToCreate, $fct),array($valueUser));
					}
				}
				$users[$userToCreate->getUsername()] = $userToCreate;
				//$this->get('user_service')->addUser($userToCreate);
			}
		}
		
		
		foreach($yml as $key => $value)
		{	
			$ymlKey = 'figure';
			if (substr($key, 0, strlen($ymlKey)) == $ymlKey)
			{
				$typeFigures = array();
				$figureToCreate = new Figure();
				foreach($value as $keyF => $valueF)
				{
					$fct = 'set'.ucfirst($keyF);
					if ($keyF == 'updatedate')
					{
						$dateUpdate = \DateTime::createFromFormat($format, $valueF);
						call_user_func_array(array($figureToCreate, $fct),array($dateUpdate));		
					}
					else if ($keyF == 'typeFigure')
					{
						$typeFigures = $valueF;
					}
					else if ($keyF == 'user')
					{
						if (array_key_exists($valueF, $users))
						{
							$figureToCreate->setUser($users[$valueF]);
						}
						else
						{
							$userByName = $this->getApplication()->getKernel()->getContainer()->get('user_service')->getUserByName($valueF);
							if (isset($userByName))
							{
								$figureToCreate->setUser($userByName);
							}
							else
							{
								$output->writeln('User ' . $valueF. ' is not define.');
								return;
							}
						}
					}
					else
					{
						call_user_func_array(array($figureToCreate, $fct),array($valueF));	
					}
				}
				$figures[$figureToCreate->getName()] = $figureToCreate;
				$typeFigureByFigures[$figureToCreate->getName()] = $typeFigures;
			}
		}
		
		foreach($yml as $key => $value)
		{
			$ymlKey = 'comment';
			$commentToCreate = new Comment();
			if (substr($key, 0, strlen($ymlKey)) == $ymlKey)
			{
				foreach($value as $keyC => $valueC)
				{
					if ($keyC == 'user')
					{
						if (array_key_exists($valueC, $users))
						{
							$commentToCreate->setUser($users[$valueF]);
						}
						else
						{
							$userByName = $this->getApplication()->getKernel()->getContainer()->get('user_service')->getUserByName($valueF);
							if (isset($userByName))
							{
								$commentToCreate->setUser($userByName);
							}
							else
							{
								$output->writeln('User ' . $valueF. ' is not define.');
								return;
							}
						}
					}
					else if ($keyC == 'figure')
					{
						if (array_key_exists($valueC, $figures))
						{
							$commentToCreate->setFigure($figures[$valueF]);
						}
						else
						{
							$figureByName = $this->getApplication()->getKernel()->getContainer()->get('figure_service')->getFigurebyName($valueF);
							if (isset($figureByName))
							{
								$commentToCreate->setFigure($figureByName);
							}
							else
							{
								$output->writeln('Figure ' . $valueF. ' is not define.');
								return;
							}
						}
					}
					if ($keyF == 'updatedate')
					{
						$dateUpdate = \DateTime::createFromFormat($format, $valueC);
						call_user_func_array(array($commentToCreate, $fct),array($dateUpdate));		
					}
					else
					{
						$commentToCreate->setComment($valueC);
					}
				}
				$comments[] = $commentToCreate;
			}
		}
		
		//erase data ?
		if (!$input->getOption('append'))
		{
			//delete comments,figure, type figure...
			$this->getApplication()->getKernel()->getContainer()->get('figure_service')->deleteAllData();
			
			$this->getApplication()->getKernel()->getContainer()->get('user_service')->deleteAllUsers();
		}
		
		//save users
		foreach($users as $key => $value)
		{
			$this->getApplication()->getKernel()->getContainer()->get('user_service')->addUser($value);
		}
		
		//save figures
		foreach($figures as $key => $value)
		{
			$output->writeln(var_dump($value));
			$typeFigureToAdd = array();
			if (array_key_exists($value->getName(),$typeFigureByFigures))
			{
				$typeFigureToAdd = $typeFigureByFigures[$value->getName()];
			}
			
			$this->getApplication()->getKernel()->getContainer()->get('figure_service')->addFigure($value->getUser(), $value,$typeFigureToAdd);
		}
		
		
		//save comments
		foreach($comments as $key => $value)
		{
			$this->getApplication()->getKernel()->getContainer()->get('figure_service')->addComment($value);
		}
    }
	
}