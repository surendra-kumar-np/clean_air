<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
$userRole = $GLOBALS['current_user']->role_slug_url;
?>
<input type="hidden" value="<?=$userRole?>" name="userRole" class="userRole" />
<input type="hidden" value="<?=!empty($userDetails->region)?$userDetails->region:''?>" name="uregion" class="uregion" />
<input type="hidden" value="<?=!empty($userDetails->sub_region)?$userDetails->sub_region:''?>" name="usub_region" class="usub_region" />
<input type="hidden" value="<?=!empty($userDetails->staffid)?$userDetails->staffid:''?>" name="ustaffid" class="ustaffid" />
<input type="hidden" class="dashboard" name="dashboard" value="dashboard" />
<div id="wrapper">
    <div class="content">
        <div class="new-dashboard">
        <!-- <h2 class="action-head"><?php //echo _l('summary'); ?> <a href="<?php //echo admin_url('dashboard/mapview') ?>" class="gm-filter-container" style="float: right; width:10%"><button class="" type="button"><?php //echo _l('view_on_map'); ?></button></a></h2> -->
		<h2 class="action-head" style="text-align:right;"><?php //echo _l('summary'); ?> <a href="<?php echo admin_url('dashboard/mapview') ?>" class="gm-filter-container" style=" width:10%"><button class="" type="button"><?php echo _l('view_on_map'); ?></button></a></h2>

            <div class="summary-section">
            <div class="total-issues-bg" onclick="reportFilter(7)">
                    <!-- <figure>
                        <img src="<?php echo base_url('assets/images/esc.png') ?>" alt="">
                    </figure> -->
                    <div>
                        <label class="total_act">
                            <?php echo $total_activity; ?>
                        </label>
                        <span><?php echo _l('total_issues'); ?></span>
                    </div>
                    <span class="icon-circle">

                        <svg viewBox="0 0 50 50">
                            <g id="Group_2757" data-name="Group 2757" transform="translate(-389 -146)">
                                <circle id="Ellipse_1" data-name="Ellipse 1" cx="25" cy="25" r="25"
                                    transform="translate(389 146)" fill="#fff" />
                                <g id="Group_2732" data-name="Group 2732" transform="translate(908.987 728.774)">
                                    <path id="Path_19" data-name="Path 19"
                                        d="M-489.031-486.227H-505.9a1.227,1.227,0,0,1-.819-1.219q.01-10.728,0-21.456a1.089,1.089,0,0,1,1.217-1.221q2.018,0,4.036,0,6.012,0,12.024,0a1.236,1.236,0,0,1,.86.248,1.209,1.209,0,0,1,.388,1q-.007,10.645,0,21.289A1.26,1.26,0,0,1-489.031-486.227Zm-8.239-17.924c-1.447,0-2.895,0-4.342,0a.9.9,0,0,0-.955.907.9.9,0,0,0,.936.928q4.342.007,8.684,0a1.113,1.113,0,0,0,.523-.151.884.884,0,0,0,.386-1.014.906.906,0,0,0-.891-.664C-494.375-504.153-495.822-504.151-497.27-504.151Zm-.028,9.188q2.129,0,4.258,0a1.435,1.435,0,0,0,.358-.032.91.91,0,0,0,.674-.989.921.921,0,0,0-.967-.813q-4.3,0-8.6,0a.913.913,0,0,0-.992.929.914.914,0,0,0,1.01.907Zm.014-3.675h4.314a.9.9,0,0,0,.888-.551.911.911,0,0,0-.878-1.285q-4.314-.006-8.628,0a.91.91,0,0,0-.978.913.913.913,0,0,0,1,.922Q-499.427-498.637-497.283-498.639Z"
                                        transform="translate(0 -57.273)" fill="#24c8d4" />
                                    <path id="Path_20" data-name="Path 20"
                                        d="M-447.7-546.288v-.372q0-9.767,0-19.535a2.89,2.89,0,0,0-2.137-2.93,4.092,4.092,0,0,0-1.018-.11q-6.861-.01-13.721,0h-.382a2.522,2.522,0,0,1,.709-1.806,2.937,2.937,0,0,1,2.235-.951c1.494,0,2.987,0,4.481,0q4.968,0,9.936,0a2.872,2.872,0,0,1,2.809,1.843,3.266,3.266,0,0,1,.174,1.061c.01,6.641,0,13.283.011,19.924A2.849,2.849,0,0,1-447.7-546.288Z"
                                        transform="translate(-38.658)" fill="#24c8d4" />
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>
                <div class="pending-bg" onclick="reportFilter(1)">
                    <!-- <figure>
                        <img src="<?php echo base_url('assets/images/esc.png') ?>" alt="">
                    </figure> -->
                    <div>
                        <label class="new_total">
                            <?php echo $new; ?>
                        </label>
                        <span><?php echo _l('total_pending'); ?></span>
                    </div>

                    <span class="icon-circle">
                        <svg viewBox="0 0 50 50">
                            <g id="Group_2759" data-name="Group 2759" transform="translate(-695 -146)">
                                <circle id="Ellipse_2" data-name="Ellipse 2" cx="25" cy="25" r="25"
                                    transform="translate(695 146)" fill="#fff" />
                                <g id="Group_2733" data-name="Group 2733" transform="translate(1777.147 722.193)">
                                    <path id="Path_16" data-name="Path 16"
                                        d="M-980.747-565.379h12.382q0,3.137,0,6.274c0,.693.315,1.009,1.007,1.009h6.276v.316q0,9.2,0,18.4a2.527,2.527,0,0,1-2.684,2.683h-13.017a9.414,9.414,0,0,0,2.7-2.965,9.33,9.33,0,0,0,1.2-3.758h1.119q2.913,0,5.826,0a.855.855,0,0,0,.859-.507.839.839,0,0,0-.838-1.174q-3.319,0-6.638,0h-.333a9.562,9.562,0,0,0-.961-3.362h4.024q1.961,0,3.921,0a.835.835,0,0,0,.828-.511.84.84,0,0,0-.847-1.169q-4.328,0-8.655.005a.774.774,0,0,1-.641-.285,8.979,8.979,0,0,0-6.024-3.029c-.551-.049-1.111-.007-1.67-.007-.009-.078-.017-.114-.017-.15,0-3.109-.007-6.218.006-9.327a2.235,2.235,0,0,1,.973-1.9A7.153,7.153,0,0,1-980.747-565.379Zm8.772,10.2q-3.079,0-6.158,0a.847.847,0,0,0-.873.744.842.842,0,0,0,.657.912,1.656,1.656,0,0,0,.361.023H-965.9a.828.828,0,0,0,.822-.518.837.837,0,0,0-.828-1.161Q-968.938-555.186-971.975-555.182Z"
                                        transform="translate(-83.087)" fill="#ff4d00" />
                                    <path id="Path_17" data-name="Path 17"
                                        d="M-1065.182-384.229a7.575,7.575,0,0,1,7.567,7.573,7.577,7.577,0,0,1-7.56,7.553,7.578,7.578,0,0,1-7.567-7.574A7.576,7.576,0,0,1-1065.182-384.229Zm.844,6.723c0-.843,0-1.653,0-2.463a.845.845,0,0,0-.836-.9.842.842,0,0,0-.842.891q-.006,1.624,0,3.248a.842.842,0,0,0,.888.9q1.064.007,2.128,0a.846.846,0,0,0,.9-.83.847.847,0,0,0-.913-.849C-1063.44-377.508-1063.868-377.506-1064.339-377.506Z"
                                        transform="translate(0 -167.591)" fill="#ff4d00" />
                                    <path id="Path_18" data-name="Path 18" d="M-765.555-557.31l4.986,4.985h-4.986Z"
                                        transform="translate(-284.195 -7.465)" fill="#ff4d00" />
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>
                <div class="resolved-bg" onclick="reportFilter(3)">
                        <!-- <figure>
                            <img src="<?php echo base_url('assets/images/esc.png') ?>" alt="">
                        </figure> -->
                        <div>
                            <label class="closed_total">
                                <?php echo $closed; ?>
                            </label>
                            <span><?php echo _l('total_closed'); ?></span>
                        </div>
                        <span class="icon-circle">
                            <svg viewBox="0 0 50 50">
                                <g id="Group_2722" data-name="Group 2722" transform="translate(-1353 -161)">
                                    <circle id="Ellipse_3" data-name="Ellipse 3" cx="25" cy="25" r="25"
                                        transform="translate(1353 161)" fill="#fff" />
                                    <g id="Group_2734" data-name="Group 2734" transform="translate(408.811 677.661)">
                                        <path id="Path_26" data-name="Path 26"
                                            d="M1044.65-427.737c0,1.042.021,1.092-.967,1.407a2.21,2.21,0,0,0-1.754,1.806,5.79,5.79,0,0,1-.7,1.534.926.926,0,0,0-.051.863c.225.518.431,1.044.661,1.56a.362.362,0,0,1-.069.486c-.377.347-.69.9-1.123,1.011-.4.106-.929-.242-1.4-.4a5.3,5.3,0,0,1-.506-.219.964.964,0,0,0-.863.047c-.762.349-1.538.668-2.323.962a.963.963,0,0,0-.639.58c-.2.516-.427,1.018-.616,1.536a.469.469,0,0,1-.5.34c-1.551.076-1.551.081-2.115-1.337-.073-.183-.157-.362-.22-.548a.928.928,0,0,0-.644-.573c-.784-.3-1.543-.656-2.329-.947a1.353,1.353,0,0,0-.813-.05,12.407,12.407,0,0,0-1.564.647.409.409,0,0,1-.55-.1c-.3-.318-.6-.631-.91-.93a.444.444,0,0,1-.081-.592c.223-.475.411-.967.625-1.447a1.009,1.009,0,0,0-.042-.9c-.349-.761-.664-1.539-.961-2.323a.941.941,0,0,0-.552-.617c-.539-.209-1.079-.417-1.6-.661a.565.565,0,0,1-.279-.393c-.032-.444,0-.893,0-1.34a.434.434,0,0,1,.337-.456c.507-.179,1-.4,1.5-.593a.988.988,0,0,0,.6-.666c.285-.774.6-1.537.948-2.286a1.012,1.012,0,0,0,.048-.9c-.222-.5-.42-1.02-.648-1.523a.384.384,0,0,1,.077-.518c.329-.3.644-.62.958-.937a.422.422,0,0,1,.556-.089,11.589,11.589,0,0,0,1.525.638,1.352,1.352,0,0,0,.815-.042c.8-.295,1.571-.655,2.365-.96a.943.943,0,0,0,.613-.556c.2-.527.43-1.044.632-1.572.08-.208.185-.327.426-.319.447.016.894.015,1.341.018a.433.433,0,0,1,.453.34c.183.52.409,1.024.606,1.539a.934.934,0,0,0,.618.551c.784.3,1.563.609,2.323.96a1.019,1.019,0,0,0,.9.043c.518-.225,1.035-.454,1.564-.649a.509.509,0,0,1,.434.071,10.131,10.131,0,0,1,.99,1.014.566.566,0,0,1,.063.475c-.189.517-.416,1.021-.638,1.526a.977.977,0,0,0,.039.864c.348.762.669,1.538.965,2.321a.953.953,0,0,0,.575.642c.468.173.918.4,1.391.552.368.121.55.317.5.711A3.054,3.054,0,0,0,1044.65-427.737Zm-13.558,2.464c-.258-.282-.477-.543-.719-.782a.663.663,0,0,0-1.01-.053.681.681,0,0,0,.071,1.012c.358.368.724.727,1.087,1.089.531.529.869.517,1.331-.059q1.667-2.077,3.328-4.158c.616-.77,1.238-1.536,1.847-2.312a.676.676,0,0,0-.235-1.133.708.708,0,0,0-.826.3q-1.4,1.76-2.808,3.513Z"
                                            transform="translate(-64.006 -63.988)" fill="#168258" />
                                        <g id="Group_2735" data-name="Group 2735" transform="translate(2.467 2.467)">
                                            <path id="Path_27" data-name="Path 27"
                                                d="M960.6-509.786c.781.249,1.472.465,2.157.7a.253.253,0,0,1,.1.225c-.2.677-.41,1.349-.638,2.085-.08-.124-.132-.2-.174-.274-.268-.494-.474-.57-.977-.345a14.328,14.328,0,0,0-5.354,4.086,14.292,14.292,0,0,0-3.2,7.8c-.008.087-.021.173-.033.259-.054.391-.28.592-.623.558a.554.554,0,0,1-.493-.68,20.873,20.873,0,0,1,.355-2.256,15.423,15.423,0,0,1,8.719-10.708c.564-.27.647-.5.36-1.054C960.744-509.5,960.7-509.6,960.6-509.786Z"
                                                transform="translate(-0.266)" fill="#168258" />
                                            <path id="Path_28" data-name="Path 28"
                                                d="M1179.771-309.046c-.771-.244-1.455-.457-2.133-.684a.249.249,0,0,1-.111-.217c.2-.686.414-1.367.645-2.115.089.154.143.243.193.335.235.431.461.512.91.316a13.949,13.949,0,0,0,4.119-2.73,14.345,14.345,0,0,0,4.477-9.16c.011-.108.025-.217.042-.324a.542.542,0,0,1,.6-.506.533.533,0,0,1,.508.6,17.689,17.689,0,0,1-.282,2,15.469,15.469,0,0,1-8.754,11.023c-.627.3-.7.5-.377,1.117C1179.657-309.3,1179.694-309.212,1179.771-309.046Z"
                                                transform="translate(-206.666 -169.425)" fill="#168258" />
                                            <path id="Path_29" data-name="Path 29"
                                                d="M1151.764-500.223c-.151.48-.278.89-.408,1.3-.082.259-.156.522-.257.774-.03.076-.156.184-.208.169-.7-.2-1.387-.417-2.109-.64a.711.711,0,0,1,.111-.094c.74-.328.705-.646.4-1.264a14.355,14.355,0,0,0-11.176-8.279c-.3-.048-.606-.07-.908-.11a.548.548,0,0,1-.536-.611.573.573,0,0,1,.679-.5,14.946,14.946,0,0,1,3.612.721,15.509,15.509,0,0,1,9.335,8.289c.313.654.5.726,1.135.4C1151.518-500.114,1151.6-500.15,1151.764-500.223Z"
                                                transform="translate(-169.383 -0.276)" fill="#168258" />
                                            <path id="Path_30" data-name="Path 30"
                                                d="M951.063-281.147c.252-.789.469-1.481.7-2.169a.227.227,0,0,1,.2-.092c.686.2,1.369.413,2.123.645-.153.09-.24.145-.33.194-.437.236-.522.458-.325.9a14.288,14.288,0,0,0,4.551,5.756,14.5,14.5,0,0,0,7.56,2.883c.444.04.66.276.616.633a.579.579,0,0,1-.724.483,15.488,15.488,0,0,1-12.877-8.963c-.053-.107-.1-.22-.157-.323a.544.544,0,0,0-.752-.246C951.481-281.367,951.322-281.277,951.063-281.147Z"
                                                transform="translate(0 -206.588)" fill="#168258" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </div>
                   
                <div class="referred-bg" onclick="reportFilter(5)">
                    <!-- <figure>
                        <img src="<?php echo base_url('assets/images/esc.png') ?>" alt="">
                    </figure> -->
                    <div>
                        <label class="referred_total">
                            <?php echo $referred; ?>
                        </label>
                        <span><?php echo _l('total_referred'); ?></span>
                    </div>
                    <span class="icon-circle">

                        <svg viewBox="0 0 50 50">
                            <g id="Group_2750" data-name="Group 2750" transform="translate(-1353 -161)">
                                <circle id="Ellipse_3" data-name="Ellipse 3" cx="25" cy="25" r="25"
                                    transform="translate(1353 161)" fill="#fff" />
                                <g id="noun-referral-2215862" transform="translate(1362.909 171.41)">
                                    <g id="Group_2748" data-name="Group 2748" transform="translate(0)">
                                        <path id="Path_42" data-name="Path 42"
                                            d="M263.263,281.612l-2.05-2.273-2.273,2.05a.625.625,0,1,0,.837.928l.732-.66a10.965,10.965,0,0,1-12,9.322.625.625,0,1,0-.132,1.242,12.191,12.191,0,0,0,13.37-10.419l.584.647a.625.625,0,1,0,.928-.837Z"
                                            transform="translate(-233.242 -263.291)" fill="#a57925" />
                                        <path id="Path_43" data-name="Path 43"
                                            d="M268.488,53.058v.625H281.61v-.625a6.572,6.572,0,0,0-4.861-6.337,3.811,3.811,0,1,0-3.4,0A6.572,6.572,0,0,0,268.488,53.058Z"
                                            transform="translate(-252.617 -38.437)" fill="#a57925" />
                                        <path id="Path_44" data-name="Path 44"
                                            d="M57.7,25.034l-2-2.317a.625.625,0,1,0-.946.817l.537.622A12.215,12.215,0,0,0,42.246,35.169a.626.626,0,0,0,.563.682c.02,0,.04,0,.06,0a.626.626,0,0,0,.622-.565,10.962,10.962,0,0,1,11.854-9.872l-.78.674a.625.625,0,0,0,.817.946Z"
                                            transform="translate(-40.509 -22.501)" fill="#a57925" />
                                        <path id="Path_45" data-name="Path 45"
                                            d="M22.749,269.721a3.811,3.811,0,1,0-3.4,0,6.572,6.572,0,0,0-4.86,6.337v.625H27.61v-.625A6.572,6.572,0,0,0,22.749,269.721Z"
                                            transform="translate(-14.488 -247.503)" fill="#a57925" />
                                    </g>
                                </g>
                            </g>
                        </svg>

                    </span>
                </div>
                <div class="longTerm-bg" onclick="reportFilter(10)">
                    <!-- <figure>
                        <img src="<?php echo base_url('assets/images/esc.png') ?>" alt="">
                    </figure> -->
                    <div>
                        <label class="longterm_total">
                            <?php echo $longterm; ?>
                        </label>
                        <span><?php echo _l('total_longterm'); ?></span>
                    </div>
                    <span class="icon-circle">

                        <svg viewBox="0 0 50 50">
                            <g id="Group_2758" data-name="Group 2758" transform="translate(-394 -266)">
                                <g id="Group_2756" data-name="Group 2756">
                                    <circle id="Ellipse_5" data-name="Ellipse 5" cx="25" cy="25" r="25"
                                        transform="translate(394 266)" fill="#fff" />
                                    <path id="Path_31" data-name="Path 31"
                                        d="M-1517.49-516.174a.912.912,0,0,1,.091.038,10.856,10.856,0,0,1,3.483,2.351,10.935,10.935,0,0,1,2.351,3.486,10.9,10.9,0,0,1,.861,4.268,10.9,10.9,0,0,1-.861,4.267,10.912,10.912,0,0,1-2.351,3.486,10.959,10.959,0,0,1-3.483,2.351,10.906,10.906,0,0,1-4.267.861,10.907,10.907,0,0,1-4.268-.861,10.855,10.855,0,0,1-3.483-2.351,10.931,10.931,0,0,1-2.351-3.486,10.907,10.907,0,0,1-.861-4.267,10.907,10.907,0,0,1,.861-4.268,10.913,10.913,0,0,1,2.351-3.486,10.962,10.962,0,0,1,3.483-2.351,10.909,10.909,0,0,1,4.268-.861h11.888V-520.1h-11.888a14.082,14.082,0,0,0-14.068,14.068,14.089,14.089,0,0,0,8.6,12.959,14.009,14.009,0,0,0,5.472,1.109h0a14.082,14.082,0,0,0,14.068-14.068,13.934,13.934,0,0,0-4.317-10.139Z"
                                        transform="translate(1938.539 798.372)" fill="#044faf" />
                                </g>
                                <path id="Path_32" data-name="Path 32"
                                    d="M-1478.821-476.951a10.031,10.031,0,0,0-4.294-1.151v1.162a.41.41,0,0,1-.412.411.41.41,0,0,1-.411-.411V-478.1a10.085,10.085,0,0,0-4.294,1.151l.583,1.006a.413.413,0,0,1-.152.564.411.411,0,0,1-.206.057.412.412,0,0,1-.358-.206l-.579-1.006a10.227,10.227,0,0,0-3.143,3.143l1.006.579a.412.412,0,0,1,.152.564.408.408,0,0,1-.358.206.447.447,0,0,1-.206-.053l-1.006-.583a10.029,10.029,0,0,0-1.151,4.294h1.162a.41.41,0,0,1,.411.411.41.41,0,0,1-.411.411h-1.162a10.081,10.081,0,0,0,1.151,4.294l1.006-.583a.412.412,0,0,1,.564.152.412.412,0,0,1-.152.564l-1.006.579a10.225,10.225,0,0,0,3.143,3.143l.579-1.006a.412.412,0,0,1,.564-.152.413.413,0,0,1,.152.564l-.583,1.006a10.029,10.029,0,0,0,4.294,1.151v-1.162a.41.41,0,0,1,.411-.412.41.41,0,0,1,.412.412v1.162a10.069,10.069,0,0,0,4.294-1.151l-.583-1.006a.412.412,0,0,1,.152-.564.412.412,0,0,1,.564.152l.579,1.006a10.228,10.228,0,0,0,3.144-3.143l-1.006-.579a.416.416,0,0,1-.152-.564.415.415,0,0,1,.564-.152l1.006.583a10.028,10.028,0,0,0,1.151-4.294h-1.162a.41.41,0,0,1-.411-.411.41.41,0,0,1,.411-.411h1.162a10.083,10.083,0,0,0-1.151-4.294l-1.006.583a.4.4,0,0,1-.206.053.412.412,0,0,1-.358-.206.412.412,0,0,1,.152-.564l1.006-.579a10.23,10.23,0,0,0-3.144-3.143l-.579,1.006a.408.408,0,0,1-.358.206.411.411,0,0,1-.206-.057.416.416,0,0,1-.152-.564Zm2.762,8.566a.41.41,0,0,1,.412.411.41.41,0,0,1-.412.411h-5.715a1.806,1.806,0,0,1-1.753,1.387,1.8,1.8,0,0,1-1.8-1.8,1.806,1.806,0,0,1,1.387-1.753v-4.283a.41.41,0,0,1,.411-.411.41.41,0,0,1,.412.411v4.287a1.808,1.808,0,0,1,1.341,1.341h5.715Z"
                                    transform="translate(1900.401 760.308)" fill="#044faf" />
                                <path id="Path_33" data-name="Path 33" d="M-1249.983-548.645v8.451l5.609-4.226Z"
                                    transform="translate(1679.568 824.24)" fill="#044faf" />
                            </g>
                        </svg>

                    </span>
                </div>
                

                <!-- <div class="new">
                    <figure>
                        <img src="<?php echo base_url('assets/images/new-ticket.png') ?>" alt="">
                    </figure>
                    <div>
                        <label class="new_total"><?php //echo $new; 
                        ?></label>
                        <span>New</span>
                    </div>
                </div> -->
                
                <div class="resolved-bg" onclick="reportFilter(6)">
                        <!-- <figure>
                            <img src="<?php echo base_url('assets/images/esc.png') ?>" alt="">
                        </figure> -->
                        <div>
                            <label class="resolved_total">
                                <?php echo $reopen; ?>
                            </label>
                            <span><?php echo _l('total_reopen'); ?></span>
                        </div>
                        <span class="icon-circle">
                            <svg viewBox="0 0 50 50">
                                <g id="Group_2722" data-name="Group 2722" transform="translate(-1353 -161)">
                                    <circle id="Ellipse_3" data-name="Ellipse 3" cx="25" cy="25" r="25"
                                        transform="translate(1353 161)" fill="#fff" />
                                    <g id="Group_2734" data-name="Group 2734" transform="translate(408.811 677.661)">
                                        <path id="Path_26" data-name="Path 26"
                                            d="M1044.65-427.737c0,1.042.021,1.092-.967,1.407a2.21,2.21,0,0,0-1.754,1.806,5.79,5.79,0,0,1-.7,1.534.926.926,0,0,0-.051.863c.225.518.431,1.044.661,1.56a.362.362,0,0,1-.069.486c-.377.347-.69.9-1.123,1.011-.4.106-.929-.242-1.4-.4a5.3,5.3,0,0,1-.506-.219.964.964,0,0,0-.863.047c-.762.349-1.538.668-2.323.962a.963.963,0,0,0-.639.58c-.2.516-.427,1.018-.616,1.536a.469.469,0,0,1-.5.34c-1.551.076-1.551.081-2.115-1.337-.073-.183-.157-.362-.22-.548a.928.928,0,0,0-.644-.573c-.784-.3-1.543-.656-2.329-.947a1.353,1.353,0,0,0-.813-.05,12.407,12.407,0,0,0-1.564.647.409.409,0,0,1-.55-.1c-.3-.318-.6-.631-.91-.93a.444.444,0,0,1-.081-.592c.223-.475.411-.967.625-1.447a1.009,1.009,0,0,0-.042-.9c-.349-.761-.664-1.539-.961-2.323a.941.941,0,0,0-.552-.617c-.539-.209-1.079-.417-1.6-.661a.565.565,0,0,1-.279-.393c-.032-.444,0-.893,0-1.34a.434.434,0,0,1,.337-.456c.507-.179,1-.4,1.5-.593a.988.988,0,0,0,.6-.666c.285-.774.6-1.537.948-2.286a1.012,1.012,0,0,0,.048-.9c-.222-.5-.42-1.02-.648-1.523a.384.384,0,0,1,.077-.518c.329-.3.644-.62.958-.937a.422.422,0,0,1,.556-.089,11.589,11.589,0,0,0,1.525.638,1.352,1.352,0,0,0,.815-.042c.8-.295,1.571-.655,2.365-.96a.943.943,0,0,0,.613-.556c.2-.527.43-1.044.632-1.572.08-.208.185-.327.426-.319.447.016.894.015,1.341.018a.433.433,0,0,1,.453.34c.183.52.409,1.024.606,1.539a.934.934,0,0,0,.618.551c.784.3,1.563.609,2.323.96a1.019,1.019,0,0,0,.9.043c.518-.225,1.035-.454,1.564-.649a.509.509,0,0,1,.434.071,10.131,10.131,0,0,1,.99,1.014.566.566,0,0,1,.063.475c-.189.517-.416,1.021-.638,1.526a.977.977,0,0,0,.039.864c.348.762.669,1.538.965,2.321a.953.953,0,0,0,.575.642c.468.173.918.4,1.391.552.368.121.55.317.5.711A3.054,3.054,0,0,0,1044.65-427.737Zm-13.558,2.464c-.258-.282-.477-.543-.719-.782a.663.663,0,0,0-1.01-.053.681.681,0,0,0,.071,1.012c.358.368.724.727,1.087,1.089.531.529.869.517,1.331-.059q1.667-2.077,3.328-4.158c.616-.77,1.238-1.536,1.847-2.312a.676.676,0,0,0-.235-1.133.708.708,0,0,0-.826.3q-1.4,1.76-2.808,3.513Z"
                                            transform="translate(-64.006 -63.988)" fill="#168258" />
                                        <g id="Group_2735" data-name="Group 2735" transform="translate(2.467 2.467)">
                                            <path id="Path_27" data-name="Path 27"
                                                d="M960.6-509.786c.781.249,1.472.465,2.157.7a.253.253,0,0,1,.1.225c-.2.677-.41,1.349-.638,2.085-.08-.124-.132-.2-.174-.274-.268-.494-.474-.57-.977-.345a14.328,14.328,0,0,0-5.354,4.086,14.292,14.292,0,0,0-3.2,7.8c-.008.087-.021.173-.033.259-.054.391-.28.592-.623.558a.554.554,0,0,1-.493-.68,20.873,20.873,0,0,1,.355-2.256,15.423,15.423,0,0,1,8.719-10.708c.564-.27.647-.5.36-1.054C960.744-509.5,960.7-509.6,960.6-509.786Z"
                                                transform="translate(-0.266)" fill="#168258" />
                                            <path id="Path_28" data-name="Path 28"
                                                d="M1179.771-309.046c-.771-.244-1.455-.457-2.133-.684a.249.249,0,0,1-.111-.217c.2-.686.414-1.367.645-2.115.089.154.143.243.193.335.235.431.461.512.91.316a13.949,13.949,0,0,0,4.119-2.73,14.345,14.345,0,0,0,4.477-9.16c.011-.108.025-.217.042-.324a.542.542,0,0,1,.6-.506.533.533,0,0,1,.508.6,17.689,17.689,0,0,1-.282,2,15.469,15.469,0,0,1-8.754,11.023c-.627.3-.7.5-.377,1.117C1179.657-309.3,1179.694-309.212,1179.771-309.046Z"
                                                transform="translate(-206.666 -169.425)" fill="#168258" />
                                            <path id="Path_29" data-name="Path 29"
                                                d="M1151.764-500.223c-.151.48-.278.89-.408,1.3-.082.259-.156.522-.257.774-.03.076-.156.184-.208.169-.7-.2-1.387-.417-2.109-.64a.711.711,0,0,1,.111-.094c.74-.328.705-.646.4-1.264a14.355,14.355,0,0,0-11.176-8.279c-.3-.048-.606-.07-.908-.11a.548.548,0,0,1-.536-.611.573.573,0,0,1,.679-.5,14.946,14.946,0,0,1,3.612.721,15.509,15.509,0,0,1,9.335,8.289c.313.654.5.726,1.135.4C1151.518-500.114,1151.6-500.15,1151.764-500.223Z"
                                                transform="translate(-169.383 -0.276)" fill="#168258" />
                                            <path id="Path_30" data-name="Path 30"
                                                d="M951.063-281.147c.252-.789.469-1.481.7-2.169a.227.227,0,0,1,.2-.092c.686.2,1.369.413,2.123.645-.153.09-.24.145-.33.194-.437.236-.522.458-.325.9a14.288,14.288,0,0,0,4.551,5.756,14.5,14.5,0,0,0,7.56,2.883c.444.04.66.276.616.633a.579.579,0,0,1-.724.483,15.488,15.488,0,0,1-12.877-8.963c-.053-.107-.1-.22-.157-.323a.544.544,0,0,0-.752-.246C951.481-281.367,951.322-281.277,951.063-281.147Z"
                                                transform="translate(0 -206.588)" fill="#168258" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </div>
                    
                    <div class="verified-bg" onclick="reportFilter(13)">
                        <!-- <figure>
                            <img src="<?php echo base_url('assets/images/closed.png') ?>" alt="">
                        </figure> -->
                        <div class="verificationboxWrap">
                            <div class="verificationbox">
                                <label class="verified_total">
                                    <?php echo $verified; ?>
                                </label>
                                <span>Verified</span>
                            </div>
                            <div class="verificationbox">
                                <label class="verified_total">
                                        <?php echo $resolved; ?>
                                    </label>
                                    <span>Resolved</span>
                            </div>
                            <div class="verificationbox">
                                <label class="verified_total">
                                        <?php echo $part_resolved; ?>
                                    </label>
                                    <span title="Partially Resolved">P. Resolved</span>
                            </div>
                            <div class="verificationbox">
                                <label class="verified_total">
                                        <?php echo $unresolved; ?>
                                    </label>
                                    <span>Unresolved</span>
                            </div>
                            <!-- <span><?php echo _l('total_verified'); ?></span> -->
                        </div>
                        <!-- <span class="icon-circle">

                            <svg viewBox="0 0 50 50">
                                <g id="Group_2762" data-name="Group 2762" transform="translate(-1349 -266)">
                                    <circle id="Ellipse_3" data-name="Ellipse 3" cx="25" cy="25" r="25"
                                        transform="translate(1349 266)" fill="#fff" />
                                    <g id="noun-verify-5658686" transform="translate(1359.81 276.81)">
                                        <g id="Group_2749" data-name="Group 2749" transform="translate(0 0)">
                                            <path id="Path_46" data-name="Path 46"
                                                d="M22.565,9.5a.736.736,0,0,1,0,1.038l-9.1,9.1a1.032,1.032,0,0,1-1.456,0L6.662,14.293A.734.734,0,0,1,7.7,13.256l4.809,4.81a.319.319,0,0,0,.451,0L21.54,9.486a.735.735,0,0,1,.505-.2.727.727,0,0,1,.52.215ZM16.068,2,14.614.423,13.167,1.995a.318.318,0,0,1-.413.062L10.94.907,9.956,2.788a.319.319,0,0,1-.387.178L7.517,2.325,7.052,4.407a.318.318,0,0,1-.325.261L4.579,4.579l.089,2.148a.318.318,0,0,1-.249.322l-2.095.469.642,2.051a.319.319,0,0,1-.157.376l-1.9,1,1.15,1.814a.32.32,0,0,1-.053.4L.423,14.614,1.995,16.06a.318.318,0,0,1,.062.413L.907,18.287l1.881.984a.319.319,0,0,1,.178.387L2.325,21.71l2.082.466a.318.318,0,0,1,.261.325l-.089,2.148,2.148-.089a.318.318,0,0,1,.322.249L7.518,26.9l2.051-.642a.319.319,0,0,1,.376.157l1,1.9,1.814-1.15a.32.32,0,0,1,.4.053l1.454,1.58,1.447-1.571a.318.318,0,0,1,.413-.062l1.814,1.15.984-1.881a.319.319,0,0,1,.387-.178l2.051.642.466-2.082a.318.318,0,0,1,.325-.261l2.148.089L24.559,22.5a.318.318,0,0,1,.249-.322l2.095-.469-.642-2.051a.319.319,0,0,1,.157-.376l1.9-1-1.15-1.814a.32.32,0,0,1,.053-.4l1.58-1.454-1.571-1.447a.318.318,0,0,1-.062-.413l1.15-1.814-1.881-.984a.319.319,0,0,1-.178-.387L26.9,7.517,24.82,7.052a.318.318,0,0,1-.261-.325l.089-2.148L22.5,4.668a.318.318,0,0,1-.322-.249l-.469-2.095-2.051.642a.319.319,0,0,1-.376-.157l-1-1.9-1.814,1.15a.32.32,0,0,1-.4-.053Z"
                                                transform="translate(-0.423 -0.423)" fill="#da32a8" />
                                        </g>
                                    </g>
                                </g>
                            </svg>

                        </span> -->
                    </div>
            </div>
            <input type="hidden" name="action_item_list" class="action_item_list" value="<?php echo ACTION_ITEM_LIST; ?>">
            <h2 class="action-head"><?php echo _l('total_your_actio_item');?></h2>
            <?php
            $activated ='';
            if($userRole == 'at' || $userRole == 'ar'){
                $activated = 'active';
            }
            ?>
            <ul class="nav nav-tabs dashboard-tab">
                <?php if($userRole != 'at' && $userRole != 'ar'){?> 
            <li class="active action_items_data"><a href="#action-items" data-toggle="tab"><?php echo _l('total_delayed')?> (<span class="action_items_cnt1"><?= (!empty($action_items_notview)) ? $action_items_notview : 0; ?>/</span><span id="thispageTablecount"><?=(!empty($action_items_cnt))?$action_items_cnt:0; ?></span>)</a></li>
            <?php } ?>
            <?php if($userRole=='aa'){?>
                
                <li class="recently_closed_data"><a href="#recently-closed" data-toggle="tab">Recently Closed (<span class="recently_closed_cnt"><?= (!empty($recently_closed_cnt))?$recently_closed_cnt:0 ?></span>)</a></li>
                <?php } ?>
                <?php if (in_array($userRole, ['ar'])) { ?>
                            <!-- <li class="active action_items_data_delayed"><a href="#action-delayed" data-toggle="tab">Action Delayed(<span
                                class="action_items_cnt1">
                                <?= (!empty($action_items_notview)) ? $action_items_notview : 0; ?>/<?= (!empty($action_items_cnt)) ? $action_items_cnt : 0; ?>
                            </span>)</a></li> -->
                            <li class="action_items_data_refered"><a href="#action-refered" data-toggle="tab"><?php echo _l('total_referred')?> (<span class="action_items_refered_cnt"><?= (!empty($action_items_notview_refered)) ? $action_items_notview_refered : 0; ?>/</span><span id="thispageTablecountrefer"><?= (!empty($action_items_refered_cnt)) ? $action_items_refered_cnt : 0; ?></span>)</a></li>
                            <?php }?>  
            </ul>

            <div class="tab-content">
            <?php if($userRole != 'at' && $userRole != 'ar'){?> 
                <div class="tab-pane fade active in" id="action-items">
                    <div class="dashboard-table action-items-data">
                        <!-- <div class="dashboard-heading">
                            <div class="dashboard-cell w20P">
                                <p>Action Item</p>
                            </div>
                            <div class="dashboard-cell w15P">
                                <p>Due Date</p>
                            </div>
                            <div class="dashboard-cell w30P">
                                <p>Comment</p>
                            </div>
                            <div class="dashboard-cell w15P">
                                <p>Evidence</p>
                            </div>
                            <?php if (in_array($userRole,['at','ar'])) { ?>
                                <div class="dashboard-cell w20P">
                                    <p>Action</p>
                                </div>
                            <?php } ?>
                        </div> -->
                        <div id="actionItems" class="table-row-group"></div>
                    </div>
                    <input type="hidden" id="pageno" value="0">
                    <!-- <div class="load-btn load-btn-action-item hide">
                        <!-- <a href="javascript:void(0)" class="btn loadMore-btn" id="loadMoreAction">Show More</a> -->
                        <!-- <button class="btn loadMore-btn" id="loadMoreAction">Show More</button>
                    </div> -->
                    <div class="d-block text-center">
                            <div class="pagination" id="ajexpagination">
                                <ul></ul>
                            </div>
                        </div>
                    <div class="dashboard-table no-action-item-data ">
                        <p class="text-center no-data-row"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        You have no action items.</p>
                    </div>
                </div>
                <?php } ?>
                <?php if($userRole=='aa'){?>
                <div class="tab-pane fade" id="recently-closed">
                    <div class="dashboard-table recently-closed-data">
                        <!-- <div class="dashboard-heading">
                            <div class="dashboard-cell w20P">
                                <p>Action Item</p>
                            </div>
                            <div class="dashboard-cell w15P">
                                <p>Closed Date</p>
                            </div>
                            <div class="dashboard-cell w30P">
                                <p>Raised Comment</p>
                            </div>
                            <div class="dashboard-cell w30P">
                                <p>Latest Comment</p>
                            </div>
                            <div class="dashboard-cell w15P">
                                <p>Evidence</p>
                            </div>
                            <div class="dashboard-cell w15P">
                                <p>Action</p>
                            </div>
                        </div> -->
                        <div id="recentlyClosedItems" class="table-row-group"></div>
                    </div>
                    <input type="hidden" id="nextpageno" value="0">
                    <div class="load-btn load-btn-recently-closed hide">
                        <!-- <a href="javascript:void(0)" class="btn loadMore-btn" id="recentlyClosedLoader">Show More</a> -->
                        <button class="btn loadMore-btn" id="recentlyClosedLoader">Show More</button>
                    </div>
                    <div class="dashboard-table no-recently-closed hide">
                        <p class="text-center no-data-row"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        You have no recently closed.</p>
                    </div>
                </div>
<?php } ?>
<?php if(in_array($userRole, ['ar'])){?>
                        
                    <div class="<?php echo $activated ?> tab-pane fade in" id="action-refered">
                        <div class="dashboard-table action-items-data-refered">
                            
                            <div id="actionItemsrefered" class="table-row-group"></div>
                        </div>
                        <input type="hidden" id="pageno" value="0">
                        <div class="d-block text-center">
                            <div class="pagination" id="ajexpaginationrefer">
                                <ul></ul>
                            </div>
                        </div>
                        <div class="dashboard-table no-action-item-data-refered ">
                            <p class="text-center no-data-row"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                You have no action items.</p>
                        </div>
                    </div>
<?php } ?>
                
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('admin/dashboard/dashboard_popup'); ?>

<?php init_tail(); ?>

<style>
    #loader,
    #deadlineloader {
        display: block;
        margin: auto;
    }

    /* .dashboard-row div,
    img {
        cursor: pointer;
    } */
    .pagination ul{
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  background: #fff;
  padding: 8px;
  border-radius: 50px;
  box-shadow: 0px 10px 15px rgba(0,0,0,0.1);
}
.pagination ul li{
  color: #20B2AA;
  list-style: none;
  line-height: 30px;
  text-align: center;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  user-select: none;
  transition: all 0.3s ease;
}
.pagination ul li.numb{
  list-style: none;
  height: 30px;
  width: 30px;
  margin: 0 3px;
  line-height: 30px;
  border-radius: 50%;
}
.pagination ul li.numb.first{
  margin: 0px 3px 0 -5px;
}
.pagination ul li.numb.last{
  margin: 0px -5px 0 3px;
}
.pagination ul li.dots{
  font-size: 22px;
  cursor: default;
}
.pagination ul li.btn{
  padding: 0 20px;
  border-radius: 50px;
}
.pagination li.active,
.pagination ul li.numb:hover,
.pagination ul li:first-child:hover,
.pagination ul li:last-child:hover{
  color: #fff;
  background: #20B2AA;
}
</style>
<?php $this->load->view('admin/dashboard/dashboard_scripts'); ?>
</body>
</html>