<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataBrandCategory;
use App\Models\DataBrandName;
use App\Models\DataType;
use App\Models\DataCategory;
use App\Models\DataSubCategory;
use App\Models\DataMeta;
use App\Models\DepartmentEscalationUnit;
use App\Models\DataGroupName;
use App\Models\DataFulfillmentLocation;
use App\Models\ChannelTicket;
use App\Models\DataSource;
use App\Models\DataActivity;
use App\Models\DataAuxReason;
use App\Models\DataStatusTicket;
use App\Models\DataGroupAgent;
use App\Models\DataFulfillment;
use App\Models\DataHoliday;
use App\Models\DataMaxHandle;
use App\Models\DataSite;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Department Escalation Units
        $deu1 = DepartmentEscalationUnit::create(['name' => 'CRC', 'email' => 'crc@kanmo.com', 'status' => 'Aktif']);
        $deu2 = DepartmentEscalationUnit::create(['name' => 'IT Support', 'email' => 'it@kanmo.com', 'status' => 'Aktif']);
        $deu3 = DepartmentEscalationUnit::create(['name' => 'Logistics', 'email' => 'logistics@kanmo.com', 'status' => 'Aktif']);

        // 2. Group Names (Needed for Brand Categories)
        $gn1 = DataGroupName::create(['name' => 'Kanmo Retail', 'status' => 'Aktif']);
        $gn2 = DataGroupName::create(['name' => 'Kanmo Distribution', 'status' => 'Aktif']);

        // 3. Brand Categories
        $bc1 = DataBrandCategory::create(['name' => 'Retail', 'data_group_name_id' => $gn1->id, 'status' => 'Aktif']);
        $bc2 = DataBrandCategory::create(['name' => 'Distribution', 'data_group_name_id' => $gn2->id, 'status' => 'Aktif']);

        // 4. Brand Names
        $bn1 = DataBrandName::create(['name' => 'Mothercare', 'data_brand_category_id' => $bc1->id, 'status' => 'Aktif']);
        $bn2 = DataBrandName::create(['name' => 'Early Learning Centre', 'data_brand_category_id' => $bc1->id, 'status' => 'Aktif']);
        $bn3 = DataBrandName::create(['name' => 'Gingersnaps', 'data_brand_category_id' => $bc2->id, 'status' => 'Aktif']);

        // 5. Data Types
        $dt1 = DataType::create(['name' => 'Complaint', 'data_brand_name_id' => $bn1->id, 'status' => 'Aktif']);
        $dt2 = DataType::create(['name' => 'Inquiry', 'data_brand_name_id' => $bn1->id, 'status' => 'Aktif']);
        $dt3 = DataType::create(['name' => 'Request', 'data_brand_name_id' => $bn2->id, 'status' => 'Aktif']);

        // 6. Data Categories
        $dc1 = DataCategory::create(['name' => 'Product Quality', 'data_brand_name_id' => $bn1->id, 'data_type_id' => $dt1->id, 'status' => 'Aktif']);
        $dc2 = DataCategory::create(['name' => 'Stock Availability', 'data_brand_name_id' => $bn1->id, 'data_type_id' => $dt2->id, 'status' => 'Aktif']);

        // 7. Data Meta
        $dm1 = DataMeta::create(['name' => 'Standard Inquiry', 'data_brand_name_id' => $bn1->id, 'data_type_id' => $dt2->id, 'data_category_id' => $dc2->id, 'status' => 'Aktif']);

        // 8. Data Sub Categories
        DataSubCategory::create([
            'name' => 'Defective Item', 
            'data_brand_name_id' => $bn1->id,
            'data_type_id' => $dt1->id,
            'data_category_id' => $dc1->id,
            'data_meta_id' => $dm1->id,
            'department_escalation_unit_id' => $deu1->id,
            'escalation_layer' => 'layer1',
            'sla' => 24,
            'status' => 'Aktif'
        ]);

        // 9. Other Master Data
        DataFulfillmentLocation::create(['name' => 'Warehouse A']);
        DataFulfillmentLocation::create(['name' => 'Store Jakarta']);

        ChannelTicket::create(['name' => 'Email', 'status' => 'Aktif']);
        ChannelTicket::create(['name' => 'WhatsApp', 'status' => 'Aktif']);
        ChannelTicket::create(['name' => 'Call', 'status' => 'Aktif']);

        DataSource::create(['name' => 'Organic', 'status' => 'Aktif']);
        DataSource::create(['name' => 'Social Media', 'status' => 'Aktif']);

        DataActivity::create(['name' => 'Call Customer', 'status' => 'Aktif']);
        DataActivity::create(['name' => 'Send Email', 'status' => 'Aktif']);

        DataAuxReason::create(['name' => 'Lunch Break', 'status' => 'Aktif']);
        DataAuxReason::create(['name' => 'Prayer', 'status' => 'Aktif']);
        DataAuxReason::create(['name' => 'Briefing', 'status' => 'Aktif']);

        DataStatusTicket::create(['name' => 'Open', 'status' => 'Aktif']);
        DataStatusTicket::create(['name' => 'Pending', 'status' => 'Aktif']);
        DataStatusTicket::create(['name' => 'Resolved', 'status' => 'Aktif']);
        DataStatusTicket::create(['name' => 'Closed', 'status' => 'Aktif']);

        DataGroupAgent::create(['name' => 'Agent Layer 1', 'status' => 'Aktif']);
        DataGroupAgent::create(['name' => 'Agent Supervisor', 'status' => 'Aktif']);

        DataFulfillment::create(['name' => 'Sameday', 'status' => 'Aktif']);
        DataFulfillment::create(['name' => 'Next Day', 'status' => 'Aktif']);

        DataHoliday::create(['name' => 'New Year', 'start_date' => date('Y-01-01'), 'end_date' => date('Y-01-01'), 'status' => 'Aktif']);

        DataMaxHandle::create(['name' => '5 Tickets', 'status' => 'Aktif']);

        DataSite::create(['name' => 'Jakarta HQ', 'location' => 'Jakarta', 'status' => 'Aktif']);
        DataSite::create(['name' => 'Surabaya Branch', 'location' => 'Surabaya', 'status' => 'Aktif']);
    }
}
