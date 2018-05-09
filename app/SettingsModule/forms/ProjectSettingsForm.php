<?php

use Nette\Application\UI\Form;
use App\Model\ProjectManager;
use Nette\Utils\FileSystem;

class ProjectSettingsForm
{
    /** @var ProjectManager */
    private $projectManager;


    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
    }

    public function create($subdomain)
    {
        $project = $this->projectManager->getBy('subdomain',$subdomain);
        $form = new Form;
        $form->addText('subdomain','Subdomena')
        ->setDefaultValue($project->subdomain);
        $form->addCheckbox('active','Aktivni')
            ->setDefaultValue($project->active);
        $form->addHidden('id',$project->id);
        $form->addHidden('old_subdomain',$subdomain);
        $form->addSubmit('send','Uložit');
        $form->onSuccess[] = [$this, 'projectSettingsFormSucceeded'];

        return $form;
    }

    public function projectSettingsFormSucceeded($form)
    {
        $values = $form->getValues();
        if($this->projectManager->subdomainDuplicate($values->subdomain,$values->id)){
            $values['subdomain'] = $values['subdomain'] . $values->id;
        }
        $dir = '../www/web_data/';
        if($values['old_subdomain'] !== $values['subdomain']){
            FileSystem::rename($dir . $values['old_subdomain'],$dir . $values['subdomain']);
        }
        $this->projectManager->patch($values);
    }

}