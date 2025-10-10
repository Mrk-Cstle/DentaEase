<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalChart extends Model
{
    use HasFactory;

    protected $table = 'dental_charts';

    protected $fillable = [
        'patient_id',

        // Permanent teeth (11–48)
        'tooth_11_condition', 'tooth_11_treatment',
        'tooth_12_condition', 'tooth_12_treatment',
        'tooth_13_condition', 'tooth_13_treatment',
        'tooth_14_condition', 'tooth_14_treatment',
        'tooth_15_condition', 'tooth_15_treatment',
        'tooth_16_condition', 'tooth_16_treatment',
        'tooth_17_condition', 'tooth_17_treatment',
        'tooth_18_condition', 'tooth_18_treatment',

        'tooth_21_condition', 'tooth_21_treatment',
        'tooth_22_condition', 'tooth_22_treatment',
        'tooth_23_condition', 'tooth_23_treatment',
        'tooth_24_condition', 'tooth_24_treatment',
        'tooth_25_condition', 'tooth_25_treatment',
        'tooth_26_condition', 'tooth_26_treatment',
        'tooth_27_condition', 'tooth_27_treatment',
        'tooth_28_condition', 'tooth_28_treatment',

        'tooth_31_condition', 'tooth_31_treatment',
        'tooth_32_condition', 'tooth_32_treatment',
        'tooth_33_condition', 'tooth_33_treatment',
        'tooth_34_condition', 'tooth_34_treatment',
        'tooth_35_condition', 'tooth_35_treatment',
        'tooth_36_condition', 'tooth_36_treatment',
        'tooth_37_condition', 'tooth_37_treatment',
        'tooth_38_condition', 'tooth_38_treatment',

        'tooth_41_condition', 'tooth_41_treatment',
        'tooth_42_condition', 'tooth_42_treatment',
        'tooth_43_condition', 'tooth_43_treatment',
        'tooth_44_condition', 'tooth_44_treatment',
        'tooth_45_condition', 'tooth_45_treatment',
        'tooth_46_condition', 'tooth_46_treatment',
        'tooth_47_condition', 'tooth_47_treatment',
        'tooth_48_condition', 'tooth_48_treatment',

        // Temporary teeth (51–85)
        'tooth_51_condition', 'tooth_51_treatment',
        'tooth_52_condition', 'tooth_52_treatment',
        'tooth_53_condition', 'tooth_53_treatment',
        'tooth_54_condition', 'tooth_54_treatment',
        'tooth_55_condition', 'tooth_55_treatment',

        'tooth_61_condition', 'tooth_61_treatment',
        'tooth_62_condition', 'tooth_62_treatment',
        'tooth_63_condition', 'tooth_63_treatment',
        'tooth_64_condition', 'tooth_64_treatment',
        'tooth_65_condition', 'tooth_65_treatment',

        'tooth_71_condition', 'tooth_71_treatment',
        'tooth_72_condition', 'tooth_72_treatment',
        'tooth_73_condition', 'tooth_73_treatment',
        'tooth_74_condition', 'tooth_74_treatment',
        'tooth_75_condition', 'tooth_75_treatment',

        'tooth_81_condition', 'tooth_81_treatment',
        'tooth_82_condition', 'tooth_82_treatment',
        'tooth_83_condition', 'tooth_83_treatment',
        'tooth_84_condition', 'tooth_84_treatment',
        'tooth_85_condition', 'tooth_85_treatment',

        // Periodontal Screening
        'gingivitis',
        'early_periodontitis',
        'moderate_periodontitis',
        'advanced_periodontitis',

        // Occlusion
        'occlusion_class_molar',
        'overjet',
        'overbite',
        'midline_deviation',
        'crossbite',

        // Appliances
        'appliance_orthodontic',
        'appliance_stayplate',
        'appliance_others',

        // TMD
        'tmd_clenching',
        'tmd_clicking',
        'tmd_trismus',
        'tmd_muscle_spasm',
    ];
    protected $casts = [
        'gingivitis' => 'boolean',
        'early_periodontitis' => 'boolean',
        'moderate_periodontitis' => 'boolean',
        'advanced_periodontitis' => 'boolean',
        'occlusion_class_molar' => 'boolean',
        'overjet' => 'boolean',
        'overbite' => 'boolean',
        'midline_deviation' => 'boolean',
        'crossbite' => 'boolean',
        'appliance_orthodontic' => 'boolean',
        'appliance_stayplate' => 'boolean',
        'appliance_others' => 'boolean',
        'tmd_clenching' => 'boolean',
        'tmd_clicking' => 'boolean',
        'tmd_trismus' => 'boolean',
        'tmd_muscle_spasm' => 'boolean',
    ];
    // Relation: One patient has one dental chart
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id')->withTrashed();
    }
}
