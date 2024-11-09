/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals',
    'Coditron_Reward/js/action/remove-points-from-summary'
], function (Component, totals, removeRewardPointsAction) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Coditron_Reward/summary/reward'
        },
        totals: totals.totals(),

        /**
         * @return {Number}
         */
        getPureValue: function () {
            var price = 0,
                segment;

            if (this.totals) {
                segment = totals.getSegment('reward');

                if (segment) {
                    price = segment.value;
                }
            }

            return price;
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            return  this.getFormattedPrice(this.getPureValue());
        },

        /**
         * Get reward points.
         */
        getRewardPoints: function () {
            return totals.totals()['extension_attributes']['reward_points_balance'];
        },

        /**
         * @return {Boolean}
         */
        isAvailable: function () {
            return this.isFullMode() && this.getPureValue() != 0; 
        },

        /**
         * @return {String}
         */
        getRewardPointsRemoveUrl: function () {
            return window.checkoutConfig.review.reward.removeUrl + '?_referer=' +  window.location.hash.substr(1);
        },

        /**
         * Remove Reward Points
         */
        removeRewardPoints: function () {
            return removeRewardPointsAction(this.getRewardPointsRemoveUrl());
        }
    });
});
