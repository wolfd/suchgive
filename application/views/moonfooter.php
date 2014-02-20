<span class="media-query-spaceship"></span>
<script>
    $(document).ready(function(){
        // load prefix-free
        (function($, self){

            if(!$ || !self) {
                return;
            }

            for(var i=0; i<self.properties.length; i++) {
                var property = self.properties[i],
                    camelCased = StyleFix.camelCase(property),
                    PrefixCamelCased = self.prefixProperty(property, true);

                $.cssProps[camelCased] = PrefixCamelCased;
            }

        })(window.jQuery, window.PrefixFree);

        // main suchgive moon graphic code

        // initialize global variables
        animatedBefore = false;
        storedData = 0;
        storedStatus = "";

        // get elements for the doge counters
        leftCharityAmount = $("#left-charity-mini-info-amount");
        rightCharityAmount = $("#right-charity-mini-info-amount");
        leftCharityPercent = $("#left-charity-mini-info-percent");
        rightCharityPercent = $("#right-charity-mini-info-percent");
        rewardAmount = $("#reward-amount");

        // get elements for the spaceships
        leftSpaceship = $("#spaceship-left");
        rightSpaceship = $("#spaceship-right");

        // fun media query storage thing!
        centerPointY = $(".media-query-spaceship").css("height");
        spaceshipTransformY = $(".media-query-spaceship").css("width");
        spaceshipLeftLanded = $(".media-query-spaceship").css("margin-left");
        spaceshipRightLanded = $(".media-query-spaceship").css("margin-right");

        //get the updated positions for the spaceship based on css rules defined by media queries
        $(window).resize(function(){
            centerPointY = $(".media-query-spaceship").css("height");
            spaceshipTransformY = $(".media-query-spaceship").css("width");
            spaceshipLeftLanded = $(".media-query-spaceship").css("margin-left");
            spaceshipRightLanded = $(".media-query-spaceship").css("margin-right");
            setMoonGraphic(true);
        });



        setTimeout(periodically, 100);
        setMoonGraphic(true);
    });

    // http://stackoverflow.com/questions/16227858/jquery-increment-number-using-animate-with-comma
    // Animate the element's value from x to y:
    function startNumberAnimate(element, start, end, append){
        $({someValue: start, appendVal: append, endingVal: end}).animate({someValue: end}, {
            duration: 3000,
            easing:'swing', // can be anything
            step: function() { // called on every step
                // Update the element's text with rounded-up value:
                element.html(commaSeparateNumber(Math.round(this.someValue))+this.appendVal);
            },
            complete: function() {
                element.html(commaSeparateNumber(Math.round(this.endingVal))+this.appendVal);
            }
        });
    }

    function commaSeparateNumber(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
        return val;
    }

    // periodically update the graphic
    function periodically() {
        $.ajax({
            url : '//www.suchgive.org/main/update',
            type: 'POST'
        }).done(updateMoonGraphic);
        setTimeout(periodically, 10000);
    }

    function setMoonGraphic(noTransition, data, status) {
        // deal with optional data variable
        if (typeof data === "undefined") {
            data = storedData;
            if (data === 0) {
                data = {};
                data.charity_zero_percentage = 0;
                data.charity_one_percentage = 0;
                data.charity_zero_raised = 0;
                data.charity_one_raised = 0;
                data.reward_pool_raised = 0;
            }
        } else {
            storedData = data;
            storedStatus = status;
        }
        // if noTransition is set to true, warp immediately to the new position.
        if (noTransition) {
            leftSpaceship.css("transition", "none");
            rightSpaceship.css("transition", "none");
        } else {
            leftSpaceship.css("transition", "3s ease-in-out");
            rightSpaceship.css("transition", "3s ease-in-out");
        }

        // create the angles and scales of the spaceships
        var angleLeft = 90 + (data.charity_zero_percentage / 100) * 90;
        var scaleLeft = -((data.charity_zero_percentage / 100) * 1.8) - 0.2;

        var angleRight = 90 - (data.charity_one_percentage / 100) * 90;
        var scaleRight = ((data.charity_one_percentage / 100) * 1.8) + 0.2;

        // set the positions correctly, if a charity has won, land the ship on the moon
        if (data.charity_zero_percentage >= 100) {
            if (!animatedBefore) {
                leftSpaceship.css("transition", "none");
                leftSpaceship.css("transform", "translateX("+spaceshipLeftLanded+") translateX(-100px) rotate(90deg) scale(-1)");

                setTimeout(function(){
                    leftSpaceship.css("transition", "3s ease-in-out");
                    leftSpaceship.css("transform", "translateX("+spaceshipLeftLanded+") rotate(110deg) scale(-1)");
                }, 500);

            } else {
                leftSpaceship.css("transform", "translateX("+spaceshipLeftLanded+") rotate(110deg) scale(-1)");
            }
        } else {
            // set the css transforms of the spaceships to the new value created
            leftSpaceship.css("transform", "translateY("+spaceshipTransformY+") translateY(-"+centerPointY+") rotate("+angleLeft+"deg) translateX("+centerPointY+") scale("+scaleLeft+") translateZ(0px)");
        }

        if (data.charity_one_percentage >= 100) {
            if (!animatedBefore) {
                rightSpaceship.css("transition", "none");
                rightSpaceship.css("transform", "translateX("+spaceshipRightLanded+") translateX(100px) rotate(270deg) scale(-1)");

                setTimeout(function(){
                    rightSpaceship.css("transition", "3s ease-in-out");
                    rightSpaceship.css("transform", "translateX("+spaceshipRightLanded+") rotate(250deg) scale(-1)");
                }, 500);

            } else {
                rightSpaceship.css("transform", "translateX("+spaceshipRightLanded+") rotate(250deg) scale(-1)");
            }
        } else {
            // set the css transforms of the spaceships to the new value created
            rightSpaceship.css("transform", "translateY("+spaceshipTransformY+") translateY(-"+centerPointY+") rotate("+angleRight+"deg) translateX("+centerPointY+") scale("+scaleRight+") translateZ(0px)");
        }

        // get stored data from the doge counters' amount attribute
        var leftDoge = leftCharityAmount.attr("amount");
        var rightDoge = rightCharityAmount.attr("amount");
        var leftPercent = leftCharityPercent.attr("amount");
        var rightPercent = rightCharityPercent.attr("amount");
        var rewardDoge = rewardAmount.attr("amount");

        // set new data to the doge counters' amount attribute
        leftCharityAmount.attr("amount", data.charity_zero_raised);
        rightCharityAmount.attr("amount", data.charity_one_raised);
        leftCharityPercent.attr("amount", data.charity_zero_percentage);
        rightCharityPercent.attr("amount", data.charity_one_percentage);
        rewardAmount.attr("amount", data.reward_pool_raised);

        // animate the doge counters :D
        startNumberAnimate(leftCharityAmount, leftDoge, data.charity_zero_raised, " &ETH");
        startNumberAnimate(rightCharityAmount, rightDoge, data.charity_one_raised, " &ETH");
        startNumberAnimate(leftCharityPercent, leftPercent, data.charity_zero_percentage, "&#37;");
        startNumberAnimate(rightCharityPercent, rightPercent, data.charity_one_percentage, "&#37;");
        startNumberAnimate(rewardAmount, rewardDoge, data.reward_pool_raised, " &ETH");

        if (!noTransition) {
            animatedBefore = true;
        }
    }

    function updateMoonGraphic(data, status) {
        setMoonGraphic(false, data, status);

    }
</script>