/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

define(['Coditron_Reward/js/view/summary/reward',
    'Coditron_Reward/js/action/remove-points-from-cart'
], function (Component, removeRewardPointsAction) {
    'use strict';

    return Component.extend({
        rewardPointsRemoveUrl: window.checkoutConfig.review.reward.removeUrl + '?_referer=cart',

        /**
         * @override
         */
        isAvailable: function () {
            return this.getPureValue() !== 0;
        },

        /**
         * @override
         */
        removeRewardPoints: function () {
            return removeRewardPointsAction(this.rewardPointsRemoveUrl);
        }
    });
});
