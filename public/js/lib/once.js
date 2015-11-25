/**
 * Bind a event only once
 * @version 1.0.0
 * @date June 30, 2010
 * @author Benjamin "balupton" Lupton {@link http://www.balupton.com}
 * @copyright (c) 2009-2010 Benjamin Arthur Lupton {@link http://www.balupton.com}
 * @link https://github.com/balupton/jquery-sparkle/blob/9921fcbf1cbeab7a4f2f875a91cb8548f3f65721/scripts/resources/jquery.events.js#L41
 */
define(["jquery"],function($) {
    $.fn.once = $.fn.once || function(event, data, callback){
            // Only apply a event handler once
            var $this = $(this);
            // Handle
            if ( (callback||false) ) {
                $this.unbind(event, callback);
                $this.bind(event, data, callback);
            } else {
                callback = data;
                $this.unbind(event, callback);
                $this.bind(event, callback);
            }
            // Chain
            return $this;
        };
});