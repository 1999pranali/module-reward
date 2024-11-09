/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

define([
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'mage/url',
    'Coditron_Reward/js/action/set-use-reward-points'
], function ($, Component, quote, $t, urlBuilder, setUseRewardPointsAction) {
    'use strict';
    var rewardConfig = window.checkoutConfig.payment.reward;
    var checkInput = (e) => {
        const content = $("#reward-input").val().trim();
        const containsOnlySpaces = content.replace(/\s/g, '').length === 0;
        const isValidInteger = /^-?\d+$/.test(content);
        $('#reward-button').prop('disabled', containsOnlySpaces || !isValidInteger);
    };

    $(document).on('keyup', '#reward-input', checkInput);
      
    return Component.extend({
        
        defaults: {
            template: 'Coditron_Reward/payment/reward'
        },
        label: rewardConfig.label,
        
        /**
         * @return {Boolean}
         */
        isAvailable: function () {
            var subtotal = parseFloat(quote.totals()['grand_total']),
            rewardUsedAmount = parseFloat(quote.totals()['extension_attributes']['base_reward_currency_amount']);    
            return rewardConfig.isAvailable && subtotal > 0 && rewardUsedAmount <= 0;
        },

        /**
         * Use reward points.
         */
        useRewardPoints: function () {
            var rewardPointsBalance = this.getRewardPointsBalance();
            var rewarddata = $("#reward-input").val().trim();
            var rewardPointsToUse = parseInt(rewarddata, 10);

            // Check if the input is a valid number, if it is positive, and if there are enough points to redeem
            if (!isNaN(rewardPointsToUse) && rewardPointsToUse > 0 && rewardPointsBalance >= rewardPointsToUse) {
                setUseRewardPointsAction(); 
                var rewarddata = $("#reward-input").val();
                var linkUrl = urlBuilder.build("reward/usepoint/save");
            
                $.ajax({
                url: linkUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    customdata: rewarddata
                },
                complete: function(response) {      
                console.log("Response: ", response);
                }
            });
            } else {
                alert('Insufficient reward points balance.');
            }
        },

        checkRewardAvailability: function () {
            var isRewardAvailable = this.isAvailable();
            $('#redeem-button').toggle(isRewardAvailable);
        },

        /**
         * Initialize the component.
         */
        initialize: function () {
            this._super();
            this.getRewardPointsBalance();
            this.checkRewardAvailability(); 

            // Attach the checkRewardAvailability function to quote changes
            quote.totals.subscribe(function () {
                this.checkRewardAvailability();
            }, this);
        },

        getRewardPointsBalance: function () {
            var rewardPointsBalance = window.checkoutConfig.payment.reward.rewardBalance;
            return rewardPointsBalance;
        }
        
    });
});
