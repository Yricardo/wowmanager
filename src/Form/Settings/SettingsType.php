<?php

namespace App\Form\Settings;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Helper\SettingHelper;
use App\Entity\Setting;
use App\Entity\User;
use App\Managers\SettingManager; 

class SettingsType  extends AbstractType 
{
    public function __construct(
        private readonly SettingManager $manager
    )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $settings = $this->manager->getSettings($options['user']);

        foreach ($settings as $setting)
        {
            switch ($setting->getType()){
                case SettingHelper::SETTING_TYPE_STRING :
                    $builder->add(
                        $setting->getName(),
                        TextType::class, 
                        ['data' => SettingHelper::formatValue($setting)]
                    );
                    break;
                case SettingHelper::SETTING_TYPE_INT :
                    $builder->add(
                        $setting->getName(),IntegerType::class, 
                        ['data' => SettingHelper::formatValue($setting)]
                    );
                    break;
                case SettingHelper::SETTING_TYPE_FLOAT :
                    $builder->add(
                        $setting->getName(),
                        TextType::class, 
                        ['data' => SettingHelper::formatValue($setting)]
                    );
                    break;
                case SettingHelper::SETTING_TYPE_BOOL :
                    $builder->add(
                        $setting->getName(),
                        CheckboxType::class, 
                        ['required' => false,'data' => SettingHelper::formatValue($setting)]
                    );                    
                    break;            
                default:
                    break;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
        ]);
    }
}
