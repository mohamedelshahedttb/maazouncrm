<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceExecutionStep;

class ServiceExecutionStepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedMarriageServiceSteps();
        $this->seedDivorceServiceSteps();
        $this->seedNotarizationServiceSteps();
        $this->seedTranslationServiceSteps();
        $this->seedConsultationServiceSteps();
    }

    /**
     * Seed execution steps for marriage service
     */
    protected function seedMarriageServiceSteps(): void
    {
        $marriageService = Service::where('category', Service::CATEGORY_MARRIAGE)->first();
        
        if (!$marriageService) {
            return;
        }

        $steps = [
            [
                'step_order' => 1,
                'step_name' => 'استقبال العميل والتحقق من الهوية',
                'step_description' => 'التحقق من هوية العميل والعروسة وولي الأمر',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner,location',
                'dependencies' => '',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 2,
                'step_name' => 'مراجعة المستندات المطلوبة',
                'step_description' => 'مراجعة شهادة الميلاد، الهوية الوطنية، شهادة الطلاق السابق إن وجدت',
                'estimated_duration_minutes' => 20,
                'required_resources' => 'partner',
                'dependencies' => '1',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 3,
                'step_name' => 'إعداد عقد الزواج',
                'step_description' => 'كتابة عقد الزواج بالتفاصيل المطلوبة',
                'estimated_duration_minutes' => 30,
                'required_resources' => 'partner,supplier',
                'dependencies' => '2',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 4,
                'step_name' => 'قراءة عقد الزواج',
                'step_description' => 'قراءة العقد على العميل والعروسة وولي الأمر',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner',
                'dependencies' => '3',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 5,
                'step_name' => 'التوقيع على العقد',
                'step_description' => 'توقيع جميع الأطراف على العقد',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '4',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 6,
                'step_name' => 'التحقق من صحة التوقيعات',
                'step_description' => 'التحقق من صحة جميع التوقيعات والمعلومات',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '5',
                'step_type' => ServiceExecutionStep::TYPE_VERIFICATION,
                'is_required' => true,
            ],
            [
                'step_order' => 7,
                'step_name' => 'تسليم نسخة العقد للعميل',
                'step_description' => 'تسليم نسخة موقعة من العقد للعميل',
                'estimated_duration_minutes' => 5,
                'required_resources' => 'partner',
                'dependencies' => '6',
                'step_type' => ServiceExecutionStep::TYPE_DELIVERY,
                'is_required' => true,
            ],
        ];

        foreach ($steps as $stepData) {
            ServiceExecutionStep::create(array_merge($stepData, [
                'service_id' => $marriageService->id,
                'is_active' => true,
            ]));
        }
    }

    /**
     * Seed execution steps for divorce service
     */
    protected function seedDivorceServiceSteps(): void
    {
        $divorceService = Service::where('category', Service::CATEGORY_DIVORCE)->first();
        
        if (!$divorceService) {
            return;
        }

        $steps = [
            [
                'step_order' => 1,
                'step_name' => 'استقبال طلب الطلاق',
                'step_description' => 'استقبال طلب الطلاق والتحقق من الهوية',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner,location',
                'dependencies' => '',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 2,
                'step_name' => 'مراجعة المستندات',
                'step_description' => 'مراجعة عقد الزواج، الهوية الوطنية، المستندات المطلوبة',
                'estimated_duration_minutes' => 25,
                'required_resources' => 'partner',
                'dependencies' => '1',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 3,
                'step_name' => 'إعداد وثيقة الطلاق',
                'step_description' => 'كتابة وثيقة الطلاق بالتفاصيل المطلوبة',
                'estimated_duration_minutes' => 30,
                'required_resources' => 'partner,supplier',
                'dependencies' => '2',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 4,
                'step_name' => 'إجراء الطلاق',
                'step_description' => 'إجراء الطلاق حسب الشريعة الإسلامية',
                'estimated_duration_minutes' => 20,
                'required_resources' => 'partner',
                'dependencies' => '3',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 5,
                'step_name' => 'التحقق من صحة الإجراءات',
                'step_description' => 'التحقق من صحة جميع الإجراءات والمستندات',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner',
                'dependencies' => '4',
                'step_type' => ServiceExecutionStep::TYPE_VERIFICATION,
                'is_required' => true,
            ],
            [
                'step_order' => 6,
                'step_name' => 'تسليم وثيقة الطلاق',
                'step_description' => 'تسليم وثيقة الطلاق للطرفين',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '5',
                'step_type' => ServiceExecutionStep::TYPE_DELIVERY,
                'is_required' => true,
            ],
        ];

        foreach ($steps as $stepData) {
            ServiceExecutionStep::create(array_merge($stepData, [
                'service_id' => $divorceService->id,
                'is_active' => true,
            ]));
        }
    }

    /**
     * Seed execution steps for notarization service
     */
    protected function seedNotarizationServiceSteps(): void
    {
        $notarizationService = Service::where('category', Service::CATEGORY_NOTARIZATION)->first();
        
        if (!$notarizationService) {
            return;
        }

        $steps = [
            [
                'step_order' => 1,
                'step_name' => 'استقبال المستندات',
                'step_description' => 'استقبال المستندات المطلوب تصديقها',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner,location',
                'dependencies' => '',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 2,
                'step_name' => 'مراجعة المستندات',
                'step_description' => 'مراجعة صحة المستندات والمعلومات',
                'estimated_duration_minutes' => 20,
                'required_resources' => 'partner',
                'dependencies' => '1',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 3,
                'step_name' => 'إجراء التصديق',
                'step_description' => 'إجراء التصديق الرسمي على المستندات',
                'estimated_duration_minutes' => 25,
                'required_resources' => 'partner',
                'dependencies' => '2',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 4,
                'step_name' => 'التحقق من التصديق',
                'step_description' => 'التحقق من صحة التصديق والمعلومات',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '3',
                'step_type' => ServiceExecutionStep::TYPE_VERIFICATION,
                'is_required' => true,
            ],
            [
                'step_order' => 5,
                'step_name' => 'تسليم المستندات المصدقة',
                'step_description' => 'تسليم المستندات المصدقة للعميل',
                'estimated_duration_minutes' => 5,
                'required_resources' => 'partner',
                'dependencies' => '4',
                'step_type' => ServiceExecutionStep::TYPE_DELIVERY,
                'is_required' => true,
            ],
        ];

        foreach ($steps as $stepData) {
            ServiceExecutionStep::create(array_merge($stepData, [
                'service_id' => $notarizationService->id,
                'is_active' => true,
            ]));
        }
    }

    /**
     * Seed execution steps for translation service
     */
    protected function seedTranslationServiceSteps(): void
    {
        $translationService = Service::where('category', Service::CATEGORY_TRANSLATION)->first();
        
        if (!$translationService) {
            return;
        }

        $steps = [
            [
                'step_order' => 1,
                'step_name' => 'استقبال النص المطلوب ترجمته',
                'step_description' => 'استقبال النص والتحقق من اللغة المطلوبة',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner,location',
                'dependencies' => '',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 2,
                'step_name' => 'تحليل النص',
                'step_description' => 'تحليل النص وتحديد المصطلحات التقنية',
                'estimated_duration_minutes' => 30,
                'required_resources' => 'partner',
                'dependencies' => '1',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 3,
                'step_name' => 'إجراء الترجمة',
                'step_description' => 'ترجمة النص باللغة المطلوبة',
                'estimated_duration_minutes' => 60,
                'required_resources' => 'partner',
                'dependencies' => '2',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 4,
                'step_name' => 'مراجعة الترجمة',
                'step_description' => 'مراجعة دقة الترجمة واللغة',
                'estimated_duration_minutes' => 30,
                'required_resources' => 'partner',
                'dependencies' => '3',
                'step_type' => ServiceExecutionStep::TYPE_VERIFICATION,
                'is_required' => true,
            ],
            [
                'step_order' => 5,
                'step_name' => 'تسليم الترجمة',
                'step_description' => 'تسليم النص المترجم للعميل',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '4',
                'step_type' => ServiceExecutionStep::TYPE_DELIVERY,
                'is_required' => true,
            ],
        ];

        foreach ($steps as $stepData) {
            ServiceExecutionStep::create(array_merge($stepData, [
                'service_id' => $translationService->id,
                'is_active' => true,
            ]));
        }
    }

    /**
     * Seed execution steps for consultation service
     */
    protected function seedConsultationServiceSteps(): void
    {
        $consultationService = Service::where('category', Service::CATEGORY_CONSULTATION)->first();
        
        if (!$consultationService) {
            return;
        }

        $steps = [
            [
                'step_order' => 1,
                'step_name' => 'استقبال العميل',
                'step_description' => 'استقبال العميل والتعرف على المشكلة',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner,location',
                'dependencies' => '',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 2,
                'step_name' => 'فهم المشكلة',
                'step_description' => 'الاستماع لفهم المشكلة بالتفصيل',
                'estimated_duration_minutes' => 20,
                'required_resources' => 'partner',
                'dependencies' => '1',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 3,
                'step_name' => 'تقديم الاستشارة',
                'step_description' => 'تقديم النصائح والحلول المناسبة',
                'estimated_duration_minutes' => 40,
                'required_resources' => 'partner',
                'dependencies' => '2',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 4,
                'step_name' => 'تأكيد الفهم',
                'step_description' => 'التأكد من فهم العميل للاستشارة',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner',
                'dependencies' => '3',
                'step_type' => ServiceExecutionStep::TYPE_VERIFICATION,
                'is_required' => true,
            ],
            [
                'step_order' => 5,
                'step_name' => 'تسليم التوصيات',
                'step_description' => 'تسليم التوصيات المكتوبة للعميل',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '4',
                'step_type' => ServiceExecutionStep::TYPE_DELIVERY,
                'is_required' => true,
            ],
        ];

        foreach ($steps as $stepData) {
            ServiceExecutionStep::create(array_merge($stepData, [
                'service_id' => $consultationService->id,
                'is_active' => true,
            ]));
        }
    }
}
