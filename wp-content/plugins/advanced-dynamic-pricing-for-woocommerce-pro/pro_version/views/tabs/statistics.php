<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var array $charts
 */
?>
<div>
    <style>
        .chart-options select {
            vertical-align: inherit;
        }

        .chart-options .chart-period-start,
        .chart-options .chart-period-end {
            padding: 4px 8px;
        }

        .chart-tooltip {
            position: absolute;
        }

        .chart-placeholder {
            margin-right: 50px;
            height: 400px;
        }

        .chart-placeholder.loading:after {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, .6);
            content: '';
        }
    </style>
    <p>
    <form class="chart-options">
        <select name="period" class="chart-period">
            <option value="this_week" selected><?php _e('this week', 'advanced-dynamic-pricing-for-woocommerce') ?></option>
            <option value="this_month"><?php _e('this month', 'advanced-dynamic-pricing-for-woocommerce') ?></option>
            <option value="custom"><?php _e('custom period', 'advanced-dynamic-pricing-for-woocommerce') ?></option>
        </select>

        <span>
                <input type="text" placeholder="yyyy-mm-dd" name="from" class="datepicker chart-period-start">
                -
                <input type="text" placeholder="yyyy-mm-dd" name="to" class="datepicker chart-period-end">
            </span>

        <select name="type" class="chart-type">
			<?php foreach ( $charts as $group => $charts_by_group ): ?>
                <optgroup label="<?php echo $group ?>">
					<?php foreach ( $charts_by_group as $key => $name ): ?>
                        <option value="<?php echo $key ?>"><?php echo $name ?></option>
					<?php endforeach; ?>
                </optgroup>
			<?php endforeach; ?>
        </select>

        <input type="submit" value="<?php _e('Update chart', 'advanced-dynamic-pricing-for-woocommerce') ?>" class="button button-secondary update-chart">
    </form>
    </p>

<!--    <div class="chart-placeholder"></div>-->
    <!-- <div class="legendholder"></div> -->


    <div id="chart-container"></div>
</div>