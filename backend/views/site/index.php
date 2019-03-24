<?php

/* @var $this yii\web\View */

$this->title = 'Deal hunters';
?>
<div class="row">
    <div class="span6">
        <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
                <h3>Today's Stats</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
                <div class="widget big-stats-container">
                    <div class="widget-content">
<!--                        <h6 class="bigstats">A fully responsive premium quality admin template built on Twitter Bootstrap by <a href="../index.html" target="_blank">EGrappler.com</a>.  These are some dummy lines to fill the area.</h6>-->
                        <div id="big_stats" class="cf">
                            <div class="stat"> <i class="icon-user"></i> <span class="value"><?=$user_count?></span> </div>
                            <!-- .stat -->

                            <div class="stat"> <i class="icon-thumbs-up-alt"></i> <span class="value">423</span> </div>
                            <!-- .stat -->

                            <div class="stat"> <i class="icon-twitter-sign"></i> <span class="value">922</span> </div>
                            <!-- .stat -->

                            <div class="stat"> <i class="icon-bullhorn"></i> <span class="value">25%</span> </div>
                            <!-- .stat -->
                        </div>
                    </div>
                    <!-- /widget-content -->

                </div>
            </div>
        </div>
        <!-- /widget -->

        <!-- /widget -->
    </div>
    <!-- /span6 -->

</div>
