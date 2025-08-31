<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\RequiredDocument;

class RequiredDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get services
        $marriageService = Service::where('category', Service::CATEGORY_MARRIAGE)->first();
        $divorceService = Service::where('category', Service::CATEGORY_DIVORCE)->first();
        $notarizationService = Service::where('category', Service::CATEGORY_NOTARIZATION)->first();

        if ($marriageService) {
            $this->createMarriageDocuments($marriageService);
        }

        if ($divorceService) {
            $this->createDivorceDocuments($divorceService);
        }

        if ($notarizationService) {
            $this->createNotarizationDocuments($notarizationService);
        }
    }

    private function createMarriageDocuments(Service $service): void
    {
        $documents = [
            [
                'document_name' => 'شهادة الميلاد للعريس',
                'description' => 'شهادة الميلاد الأصلية أو صورة مصدقة',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 1,
            ],
            [
                'document_name' => 'شهادة الميلاد للعروس',
                'description' => 'شهادة الميلاد الأصلية أو صورة مصدقة',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 2,
            ],
            [
                'document_name' => 'الهوية الوطنية للعريس',
                'description' => 'الهوية الوطنية أو جواز السفر',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 3,
            ],
            [
                'document_name' => 'الهوية الوطنية للعروس',
                'description' => 'الهوية الوطنية أو جواز السفر',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 4,
            ],
            [
                'document_name' => 'شهادة الطلاق السابق (إن وجدت)',
                'description' => 'شهادة الطلاق إذا كان أحد الطرفين متزوج سابقاً',
                'document_type' => RequiredDocument::TYPE_CONDITIONAL,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 5,
            ],
            [
                'document_name' => 'شهادة وفاة الزوج السابق (إن وجدت)',
                'description' => 'شهادة وفاة إذا كان أحد الطرفين أرمل',
                'document_type' => RequiredDocument::TYPE_CONDITIONAL,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 6,
            ],
            [
                'document_name' => 'صور شخصية حديثة',
                'description' => 'صور شخصية حديثة للعريس والعروس',
                'document_type' => RequiredDocument::TYPE_OPTIONAL,
                'file_format' => 'jpg,jpeg,png',
                'max_file_size_mb' => 10,
                'sort_order' => 7,
            ],
        ];

        foreach ($documents as $document) {
            RequiredDocument::create(array_merge($document, ['service_id' => $service->id]));
        }
    }

    private function createDivorceDocuments(Service $service): void
    {
        $documents = [
            [
                'document_name' => 'عقد الزواج الأصلي',
                'description' => 'عقد الزواج الأصلي أو صورة مصدقة',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 1,
            ],
            [
                'document_name' => 'الهوية الوطنية للزوج',
                'description' => 'الهوية الوطنية أو جواز السفر',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 2,
            ],
            [
                'document_name' => 'الهوية الوطنية للزوجة',
                'description' => 'الهوية الوطنية أو جواز السفر',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 3,
            ],
            [
                'document_name' => 'شهادة الميلاد للأبناء (إن وجدوا)',
                'description' => 'شهادات الميلاد للأبناء القاصرين',
                'document_type' => RequiredDocument::TYPE_CONDITIONAL,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 4,
            ],
            [
                'document_name' => 'إقرار بالطلاق',
                'description' => 'إقرار مكتوب بالطلاق من الطرف المطلوب',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 5,
            ],
        ];

        foreach ($documents as $document) {
            RequiredDocument::create(array_merge($document, ['service_id' => $service->id]));
        }
    }

    private function createNotarizationDocuments(Service $service): void
    {
        $documents = [
            [
                'document_name' => 'المستند المراد تصديقه',
                'description' => 'المستند الأصلي المراد تصديقه',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 10,
                'sort_order' => 1,
            ],
            [
                'document_name' => 'الهوية الوطنية للمقدم',
                'description' => 'الهوية الوطنية أو جواز السفر',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 2,
            ],
            [
                'document_name' => 'إقرار بالملكية',
                'description' => 'إقرار مكتوب بملكية المستند',
                'document_type' => RequiredDocument::TYPE_REQUIRED,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 5,
                'sort_order' => 3,
            ],
            [
                'document_name' => 'مستندات إضافية',
                'description' => 'أي مستندات إضافية قد تكون مطلوبة',
                'document_type' => RequiredDocument::TYPE_OPTIONAL,
                'file_format' => 'pdf,jpg,jpeg,png',
                'max_file_size_mb' => 10,
                'sort_order' => 4,
            ],
        ];

        foreach ($documents as $document) {
            RequiredDocument::create(array_merge($document, ['service_id' => $service->id]));
        }
    }
}
