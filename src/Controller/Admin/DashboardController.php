<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\CharacterClass;
use App\Entity\CharacterRole;
use App\Entity\Server;
use App\Entity\Character;
use App\Entity\WowVersion;
use App\Entity\Guild;
use App\Entity\AvailableRoleForClass;

#[AdminDashboard(routePath: '/admin', routeName: 'app_admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        return $this->redirectToRoute('app_admin_user_index'); // this will redirect to the list of users
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        
        //return $this->render('admin/admin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wowmanageapp')
            ->setDefaultColorScheme('dark')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('CharacterRoles', 'fas fa-list', CharacterRole::class);
        yield MenuItem::linkToCrud('Servers', 'fas fa-list', Server::class);
        yield MenuItem::linkToCrud('Characters', 'fas fa-list', Character::class);
        yield MenuItem::linkToCrud('Guild', 'fas fa-list', Guild::class);
        yield MenuItem::linkToCrud('Classes', 'fas fa-list', CharacterClass::class);
        yield MenuItem::linkToCrud('WowVersion', 'fas fa-list', WowVersion::class);
        yield MenuItem::linkToCrud('AvailableRoleForClass', 'fas fa-list', AvailableRoleForClass::class);
    }
}
