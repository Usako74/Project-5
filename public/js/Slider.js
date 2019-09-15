"use strict";

class Slider {
    constructor(){
        this.imgSlide = document.getElementsByClassName("showslider");
        this.pauseSlide = document.getElementById("button_pause");
        this.playSlide =document.getElementById("button_play");
        this.currentSlide = 1;
        this.timer = null;
        this.statusSlider = 1;
    }

    // Keystroke: Right and Left Arrows
    pressSlide(e){
        switch (e.keyCode) {
            case 39:
                this.btnRight();
                break;
            case 37:
                this.btnLeft();
                break;
        }
    }

    // Slider: Right Event
    btnRight() {
        this.imgSlide[this.currentSlide - 1].style.opacity = "0";
        this.currentSlide++;
        if (this.currentSlide > this.imgSlide.length) {
            this.currentSlide = 1;
        }
        this.imgSlide[this.currentSlide - 1].style.opacity = "1";
    }

    // Slider: Left Event
    btnLeft() {
        this.imgSlide[this.currentSlide - 1].style.opacity = "0";
        this.currentSlide--;
        if (this.currentSlide === 0) {
            this.currentSlide = this.imgSlide.length;
        }
        this.imgSlide[this.currentSlide - 1].style.opacity = "1";
    }

    // Play Event Method
    btnPlay() {
        this.pauseSlide.style.display = "block";
        this.playSlide.style.display = "none";
        this.statusSlider = 1;
    }

    // Pause Event Method
    btnPause() {
        this.playSlide.style.display = "block";
        this.pauseSlide.style.display = "none";
        this.statusSlider = 0;
    }


    // Events:
    event() {
        let that = this;
        this.timer = setInterval(function(){that.btnRight();},5000);
        // Right Arrow Click
        document.getElementById("button_right").addEventListener("click", function(){
            that.btnRight();
            if (that.statusSlider === 1) {
                clearInterval(that.timer);
                that.timer = setInterval(function(){that.btnRight()},5000);
            }
        });

        // Left Arrow Click
        document.getElementById("button_left").addEventListener("click", function(){
            that.btnLeft();
            if (that.statusSlider === 1) {
                clearInterval(that.timer);
                that.timer = setInterval(function(){that.btnRight()},5000);
            }
        });

        document.getElementById("slider").addEventListener("mouseover", function(){
            if (that.statusSlider === 0) {
                that.playSlide.style.display = "block";
            } else {
                that.playSlide.style.display = "none";
            }
        });

        document.getElementById("slider").addEventListener("mouseout", function(){
            that.playSlide.style.display = "none";
        });


        // Play Button Click
        that.playSlide.addEventListener("click", function(){
            that.timer = setInterval(function(){that.btnRight()},5000);
            that.btnPlay();
        });

        document.getElementById("slider").addEventListener("mouseover", function(){
            if (that.statusSlider === 1) {
                that.pauseSlide.style.display = "block";
            } else {
                that.pauseSlide.style.display = "none";
            }
        });

        document.getElementById("slider").addEventListener("mouseout", function(){
            that.pauseSlide.style.display = "none";
        });

        // Pause Button Click
        document.getElementById("button_pause").addEventListener("click", function(){
            clearInterval(that.timer);
            that.btnPause();
        });

        // Keystroke
        document.addEventListener("keydown", function(e){
            that.pressSlide(e);
            if (that.statusSlider === 1) {
                clearInterval(that.timer);
                that.timer = setInterval(function(){that.btnRight()},5000);
            }
        });
    }
}




