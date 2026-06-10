<?php
$bladeFile = __DIR__ . '/resources/views/admin/inquiry/edit_diet_join_patient.blade.php';
$content = file_get_contents($bladeFile);

// Look for ra_test
$search = <<<HTML
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">RA Test</label>
                                    <input type="text" id="ra_test" name="ra_test" class="form-control"
                                        placeholder="Enter RA Test value" value="{{ \$latestMeta['ra_test'] ?? '' }}">
                                </div>
HTML;

$replacement = <<<HTML
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">RA Test</label>
                                    <input type="text" id="ra_test" name="ra_test" class="form-control"
                                        placeholder="Enter RA Test value" value="{{ \$latestMeta['ra_test'] ?? '' }}">
                                </div>

                                {{-- Blood / CBC Fields --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">HB</label>
                                    <input type="text" id="hb" name="hb" class="form-control" placeholder="HB"
                                        value="{{ \$latestMeta['hb'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">TC</label>
                                    <input type="text" id="tc" name="tc" class="form-control" placeholder="TC"
                                        value="{{ \$latestMeta['tc'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PC</label>
                                    <input type="text" id="pc" name="pc" class="form-control" placeholder="PC"
                                        value="{{ \$latestMeta['pc'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">MP</label>
                                    <input type="text" id="mp_lab" name="mp_lab" class="form-control"
                                        placeholder="MP" value="{{ \$latestMeta['mp_lab'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">ESR</label>
                                    <input type="text" id="esr" name="esr" class="form-control" placeholder="ESR"
                                        value="{{ \$latestMeta['esr'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">CRP</label>
                                    <input type="text" id="crp" name="crp" class="form-control" placeholder="CRP"
                                        value="{{ \$latestMeta['crp'] ?? '' }}">
                                </div>

                                {{-- Sugar Tests --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">HB1AC</label>
                                    <input type="text" id="hb1ac" name="hb1ac" class="form-control"
                                        placeholder="HB1AC" value="{{ \$latestMeta['hb1ac'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">FBS</label>
                                    <input type="text" id="fbs" name="fbs" class="form-control" placeholder="FBS"
                                        value="{{ \$latestMeta['fbs'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PP2BS</label>
                                    <input type="text" id="pp2bs" name="pp2bs" class="form-control"
                                        placeholder="PP2BS" value="{{ \$latestMeta['pp2bs'] ?? '' }}">
                                </div>

                                {{-- Serology / Infection --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. Widal</label>
                                    <input type="text" id="s_widal" name="s_widal" class="form-control"
                                        placeholder="S. Widal" value="{{ \$latestMeta['s_widal'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">NS1 Ag</label>
                                    <input type="text" id="ns1ag" name="ns1ag" class="form-control"
                                        placeholder="NS1 Ag" value="{{ \$latestMeta['ns1ag'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Dengue IGM</label>
                                    <input type="text" id="dengue_igm" name="dengue_igm" class="form-control"
                                        placeholder="Dengue IGM" value="{{ \$latestMeta['dengue_igm'] ?? '' }}">
                                </div>

                                {{-- Vitamins --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. B12</label>
                                    <input type="text" id="s_b12" name="s_b12" class="form-control"
                                        placeholder="S. B12" value="{{ \$latestMeta['s_b12'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. D3</label>
                                    <input type="text" id="s_d3" name="s_d3" class="form-control"
                                        placeholder="S. D3" value="{{ \$latestMeta['s_d3'] ?? '' }}">
                                </div>

                                {{-- Thyroid Tests --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. T3</label>
                                    <input type="text" id="s_t3" name="s_t3" class="form-control"
                                        placeholder="S. T3" value="{{ \$latestMeta['s_t3'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. T4</label>
                                    <input type="text" id="s_t4" name="s_t4" class="form-control"
                                        placeholder="S. T4" value="{{ \$latestMeta['s_t4'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">S. TSH</label>
                                    <input type="text" id="s_tsh" name="s_tsh" class="form-control"
                                        placeholder="S. TSH" value="{{ \$latestMeta['s_tsh'] ?? '' }}">
                                </div>

                                {{-- Urine --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Urine</label>
                                    <input type="text" id="urine_lab" name="urine_lab" class="form-control"
                                        placeholder="Urine" value="{{ \$latestMeta['urine_lab'] ?? '' }}">
                                </div>

                                {{-- Any Specific Test --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Any Specific Test</label>
                                    <input type="text" id="specific_test" name="specific_test" class="form-control"
                                        placeholder="Any specific Test"
                                        value="{{ \$latestMeta['specific_test'] ?? '' }}">
                                </div>
HTML;

$content = str_replace($search, $replacement, $content);
file_put_contents($bladeFile, $content);
echo "Done";
