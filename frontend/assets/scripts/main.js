let sliderData = {
    current: 0,
    texts: [
        {
            title: "Secure",
            desc: `Elite proxies & Tor Support & Spoofed servers. No logs are kept. No Valid email is needed.
            <br><br>
            A standout feature of "Secure" is its commitment to not keeping any logs, meaning there is no record of user actions or data stored on the platform. Additionally, the service does not require a valid email for registration, further safeguarding user identity. `
        },
        {
            title: "Customizable",
            desc: `Custom origin, useragent, request type and r/s per IP are only a tiny part of the options.
            <br><br>
            From automating/scheduling attacks and controlling the number of concurrents for each to a custom header for masking your attack on the website's backend - we've got you covered.`
        },
        {
            title: "Instant Stresser",
            desc: `Powerful high bandwidth servers make your attack as stable and hard-hitting as possible. Golang backend ensures that all stress tests are started and stopped instantly.
            <br><br>
            You never have to wait another millisecond to stress them or worry about downtime with us.`
        },
        {
            title: "Advanced",
            desc: `With our self-coded solution and usage of high-quality proxies, we are currently providing the best bypasses and power stability on the market.
            <br><br>
            While fully emulating a real user, there are also numerous parameters for tuning your attack, and our professional support will help you with that.`
        }
    ]
}

function switchSlider() {
    if(sliderData.current + 1 > 3) {
        $(".progress-active").remove()
        sliderData.current = 0
    } else {
        sliderData.current = sliderData.current + 1;
    }

    $(".slider-title").html(sliderData.texts[sliderData.current].title)
    $(".slider-desc").html(sliderData.texts[sliderData.current].desc)

    $(`.slider-title`).animate({
        opacity: '1'
    }, 250)
    $(`.slider-desc`).animate({
        opacity: '1'
    }, 250, () => {
        
    })
    $(".progress-slider .progress:nth-child(" + (sliderData.current + 1) + ")").html(`<div class="progress-active progress-id-${sliderData.current}"></div>`)
    $(`.progress-id-${sliderData.current}`).animate({
        width: '100%'
    }, 5000, () => {
        $(`.slider-title`).animate({
            opacity: '0'
        }, 250)
        $(`.slider-desc`).animate({
            opacity: '0'
        }, 250, () => {
            switchSlider()
        })


    })
}

$(document).ready(() => {
    $(".slider-title").html(sliderData.texts[sliderData.current].title)
    $(".slider-desc").html(sliderData.texts[sliderData.current].desc)
    $(".progress-slider .progress:nth-child(" + (sliderData.current + 1) + ")").html(`<div class="progress-active progress-id-${sliderData.current}"></div>`)
    $(`.progress-id-${sliderData.current}`).animate({
        width: '100%'
    }, 5000, () => {
        $(`.slider-title`).animate({
            opacity: '0'
        }, 250)
        $(`.slider-desc`).animate({
            opacity: '0'
        }, 250, () => {
            switchSlider()
        })
    })
})