/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

define(['uiComponent'], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Coditron_Reward/authentication/tooltip'
        },
        isAvailable: window.checkoutConfig.authentication.reward.isAvailable,
        tooltipLearnMoreUrl: window.checkoutConfig.authentication.reward.tooltipLearnMoreUrl,
        tooltipMessage: window.checkoutConfig.authentication.reward.tooltipMessage
    });
});
