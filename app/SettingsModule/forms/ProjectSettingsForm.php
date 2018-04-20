<?php

use Nette\Application\UI\Form;
use App\Model\ProjectManager;

class ProjectSettingsForm
{
    /** @var ProjectManager */
    private $projectManager;


    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
    }

    public function create($id)
    {
        $project = $this->projectManager->get($id);
        $form = new Form;
        $form->addText('subdomain','Subdomena')
        ->setDefaultValue($project->subdomain);
        $form->addCheckbox('active','Aktivni')
            ->setDefaultValue($project->active);
        $form->addHidden('id',$id);
        $form->addSubmit('send','Uložit');
        $form->onSuccess[] = [$this, 'projectSettingsFormSucceeded'];

        return $form;
    }

    public function projectSettingsFormSucceeded($form)
    {
        $values = $form->getValues();
        if($this->projectManager->subdomainDuplicate($values->subdomain)){
            $values['subdomain'] = $values['subdomain'] . $values->id;
        }
        bdump($values);
        $values['updated_at'] = date("Y-m-d H:i:s");
        $this->projectManager->patch($values);
    }

}