<?php

namespace App\Controller\Admin;

use App\Entity\FriendLink;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class FriendLinkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FriendLink::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
        ];
    }
}
