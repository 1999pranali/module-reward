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
    'mage/dataPost'
], function ($, dataPost) {
    'use strict';

    $.widget('mage.removeRewardPoints', {

        /**
         * Create widget
         * @type {Object}
         */
        _create: function () {
            this.element.on('click', $.proxy(function () {
                this.removePoints();
            }, this));
        },

        /**
         * Remove reward points from item.
         *
         * @return void
         */
        removePoints: function () {
            dataPost().postData({
                action: this.options.removeUrl,
                data: {}
            });
        }
    });

    return $.mage.removeRewardPoints;
});
