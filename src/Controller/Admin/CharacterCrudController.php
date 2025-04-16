<?php

namespace App\Controller\Admin;

use App\Entity\Character;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CharacterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Character::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            NumberField::new('level'),
            AssociationField::new('server')->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('characterClass')->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('user')->setFormTypeOption('choice_label', 'username'),
            AssociationField::new('guild')->setFormTypeOption('choice_label', 'name')
        ];
    }
}
