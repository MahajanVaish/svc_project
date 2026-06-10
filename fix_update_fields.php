<?php
$controllerFile = __DIR__ . '/app/Http/Controllers/Admin/InquiryDietChartController.php';
$content = file_get_contents($controllerFile);

$search = <<<PHP
                // Laboratory Investigation section
                's_insulin',
                'sgpt',
                's_creatinine',
                's_uric_acid',
                'ra_test',
                'usg_abdomen',
                'chest_xray',
                'mri_ct_scan',
PHP;

$replace = <<<PHP
                // Laboratory Investigation section
                's_insulin',
                'sgpt',
                's_creatinine',
                's_uric_acid',
                'ra_test',
                'hb',
                'tc',
                'pc',
                'mp_lab',
                'esr',
                'crp',
                'hb1ac',
                'fbs',
                'pp2bs',
                's_widal',
                'ns1ag',
                'dengue_igm',
                's_b12',
                's_d3',
                's_t3',
                's_t4',
                's_tsh',
                'urine_lab',
                'specific_test',
                'usg_abdomen',
                'chest_xray',
                'mri_ct_scan',
                // Lipid Profile section
                's_cholesterol',
                's_triglycerides',
                'hdl',
                'ldl',
                'vldl',
                'non_hdl_c',
                'chol_hdl_ratio',
PHP;

$content = str_replace($search, $replace, $content);
file_put_contents($controllerFile, $content);

$bladeFile = __DIR__ . '/resources/views/admin/inquiry/edit_diet_join_patient.blade.php';
$bladeContent = file_get_contents($bladeFile);

// Look for MRI-CT Scan in edit_diet_join_patient
$searchBlade = <<<HTML
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">MRI-CT Scan</label>
                                    <textarea id="mri_ct_scan" name="mri_ct_scan" class="form-control" rows="3"
                                        placeholder="Enter MRI-CT Scan details">{{ \$latestMeta['mri_ct_scan'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
HTML;

$replaceBlade = <<<HTML
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">MRI-CT Scan</label>
                                    <textarea id="mri_ct_scan" name="mri_ct_scan" class="form-control" rows="3"
                                        placeholder="Enter MRI-CT Scan details">{{ \$latestMeta['mri_ct_scan'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Lipid Profile Section Inside Laboratory Investigation -->
                            <div class="lipid-profile-header mt-3">
                                <label>Lipid Profile :</label>
                                <div class="lipid-header-line"></div>
                            </div>

                            <div class="row mb-3 mt-2">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S.Cholesterol</label>
                                    <input type="text" id="s_cholesterol" name="s_cholesterol" class="form-control"
                                        value="{{ \$latestMeta['s_cholesterol'] ?? '' }}"
                                        placeholder="Enter S.Cholesterol">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S.Triglycerides</label>
                                    <input type="text" id="s_triglycerides" name="s_triglycerides"
                                        class="form-control" value="{{ \$latestMeta['s_triglycerides'] ?? '' }}"
                                        placeholder="Enter S.Triglycerides">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">HDL</label>
                                    <input type="text" id="hdl" name="hdl" class="form-control"
                                        value="{{ \$latestMeta['hdl'] ?? '' }}" placeholder="Enter HDL">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">LDL</label>
                                    <input type="text" id="ldl" name="ldl" class="form-control"
                                        value="{{ \$latestMeta['ldl'] ?? '' }}" placeholder="Enter LDL">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">VLDL</label>
                                    <input type="text" id="vldl" name="vldl" class="form-control"
                                        value="{{ \$latestMeta['vldl'] ?? '' }}" placeholder="Enter VLDL">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Non-HDL C</label>
                                    <input type="text" id="non_hdl_c" name="non_hdl_c" class="form-control"
                                        value="{{ \$latestMeta['non_hdl_c'] ?? '' }}" placeholder="Enter Non-HDL C">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Chol/HDL</label>
                                    <input type="text" id="chol_hdl_ratio" name="chol_hdl_ratio"
                                        class="form-control" value="{{ \$latestMeta['chol_hdl_ratio'] ?? '' }}"
                                        placeholder="Enter Chol/HDL ratio">
                                </div>
                            </div>

                        </div>
                    </div>
HTML;

$bladeContent = str_replace($searchBlade, $replaceBlade, $bladeContent);
file_put_contents($bladeFile, $bladeContent);
echo "Fields added to updateDietChart and edit_diet_join_patient blade.";
