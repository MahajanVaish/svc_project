<?php
$bladeFile = __DIR__ . '/resources/views/admin/inquiry/patient-profile.blade.php';
$content = file_get_contents($bladeFile);

$fields = [
    'hb' => 'HB',
    'tc' => 'TC',
    'pc' => 'PC',
    'mp_lab' => 'MP',
    'esr' => 'ESR',
    'crp' => 'CRP',
    'hb1ac' => 'HB1AC',
    'fbs' => 'FBS',
    'pp2bs' => 'PP2BS',
    's_widal' => 'S. WIDAL',
    'ns1ag' => 'NS1 AG',
    'dengue_igm' => 'DENGUE IGM',
    's_b12' => 'S. B12',
    's_d3' => 'S. D3',
    's_t3' => 'S. T3',
    's_t4' => 'S. T4',
    's_tsh' => 'S. TSH',
    'urine_lab' => 'URINE',
    'specific_test' => 'ANY SPECIFIC TEST',
    'mri_ct_scan' => 'MRI-CT SCAN'
];

$replacement = "";
foreach ($fields as $key => $label) {
    $replacement .= <<<HTML
                                                                                                                                @if(isset(\$optMeta['$key']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">$label</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue(\$optMeta['$key']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif\n
HTML;
}

// Find where chest_xray ends
$search = <<<HTML
                                                                                                                                @if(isset(\$optMeta['chest_xray']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">CHEST X-RAY</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue(\$optMeta['chest_xray']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
HTML;

$content = str_replace($search, $search . "\n" . $replacement, $content);

// Update the if condition to include all fields
$oldIfCondition = "@if (\$optData && !empty(\$optMeta) && (isset(\$optMeta['s_insulin']) || isset(\$optMeta['sgpt']) || isset(\$optMeta['s_creatinine']) || isset(\$optMeta['s_uric_acid']) || isset(\$optMeta['ra_test']) || isset(\$optMeta['usg_abdomen']) || isset(\$optMeta['chest_xray'])))";
$newIfCondition = "@if (\$optData && !empty(\$optMeta) && (isset(\$optMeta['s_insulin']) || isset(\$optMeta['sgpt']) || isset(\$optMeta['s_creatinine']) || isset(\$optMeta['s_uric_acid']) || isset(\$optMeta['ra_test']) || isset(\$optMeta['usg_abdomen']) || isset(\$optMeta['chest_xray']) || isset(\$optMeta['hb']) || isset(\$optMeta['tc']) || isset(\$optMeta['pc']) || isset(\$optMeta['mp_lab']) || isset(\$optMeta['esr']) || isset(\$optMeta['crp']) || isset(\$optMeta['hb1ac']) || isset(\$optMeta['fbs']) || isset(\$optMeta['pp2bs']) || isset(\$optMeta['s_widal']) || isset(\$optMeta['ns1ag']) || isset(\$optMeta['dengue_igm']) || isset(\$optMeta['s_b12']) || isset(\$optMeta['s_d3']) || isset(\$optMeta['s_t3']) || isset(\$optMeta['s_t4']) || isset(\$optMeta['s_tsh']) || isset(\$optMeta['urine_lab']) || isset(\$optMeta['specific_test']) || isset(\$optMeta['mri_ct_scan'])))";

$content = str_replace($oldIfCondition, $newIfCondition, $content);

file_put_contents($bladeFile, $content);
echo "Added missing lab fields to patient profile.";
