
<?php 
  $pagename = "Dashboard";
  include '../header.php'; 
?>
  <script src="/dash/assets/js/jquery.flot.js"></script>
  <script src="/dash/assets/js/jquery.flot.tooltip.min.js"></script>
  <script src="/dash/assets/js/jquery.flot.resize.js"></script>
  <div class="p-4 lg:px-24 text-white page-cont">
    <div class="welcome-header">
      Welcome back, <?= $_SESSION['username'] ?>
      <div class="welcome-emoji">
        <img src="/dash/assets/img/hi_emoji.png"/>
      </div>
    </div>
    <div class="welcome-subtitle">
      Check out the latest news on this page.<br>All statistics and network status are also available here.
    </div>
    <div class="grid grid-cols-3 gap-4 mt-8 place-items-center">
      <div class="item">
        <div class="progress-slider">
          <div class="progress">

          </div>
          <div class="progress">
              
          </div>
          <div class="progress">
              
          </div>
        </div>
        
        <div class="slider-content" style="display: none;">
          <div class="slider-stats">
            0
          </div>
          <div class="slider-footer">
            <span class="slider-footer-header">
              Attacks launched today
            </span>
            <span class="slider-footer-desc">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod incididunt.
            </span>
          </div>
        </div>

        <div class="form"></div>

        <div class="blur1"></div>

        <div class="blur2"></div>
      </div>
      <div class="item2 overflow-x-auto px-6 py-4">
        <table class="w-full text-left items-left justify-left serversTable">
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Network</th>
              <th scope="col">Status</th>
              <th scope="col">Usage</th>
            </tr>
          </thead>
          <tbody id="servers" class="mb-2">
            <?php include 'rest/servers/servers.php'; ?>
          </tbody>
        </table>
      </div>
      <div class="item2 methodsUsage">
        <span class="flex w-full mt-2 justify-center">loading..</span>

        <div class="grid grid-cols-2 gap-4 mt-8 place-items-center">
          <div style="max-width: 184px; max-height: 184px; float: left; position: relative; margin-left: 35px;" id="methods_cont">
            <div
              id="methods_top"
              style="width: 100%; height: 40px; position: absolute; top: 50%; left: 0; margin-top: -20px; line-height:19px; text-align: center; z-index: 1">

            </div>
            <canvas id="methodsUsage_chart" style="max-width: 184px; max-height: 184px;"></canvas>
          </div>
          <div style="max-height: 195px; overflow: auto;" id="methods_legend">
          
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mt-8">
      <div>
        <div class="welcome-header">
          Latest News
        </div>
        <div class="news-card rounded-lg">
          <div class="news-und">
 					  <ol class="ms-1">
              <?php include 'rest/news/news.php'; ?>
            </ol>
          </div>
 				</div>
      </div>
      <div>
        <div class="welcome-header">
          Network load
        </div>
        <div class="news-card rounded-lg no-scroll">
          <div class="news-und">
            <div id="realnetwork" style="padding: 0px; position: relative; height: 33vh;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="/dash/assets/js/charts.js"></script>
  <script>
    let _slider_loaded = false
    let _slider_data = {}
    let sliderData = {
      current: 0,
      texts: [
        {
          title: "Attacks launched today",
          desc: `Total attacks launched today.`,
          selector: 'todayattacks'
        },
        {
          title: "Running attacks",
          desc: `Number of attacks running in real time`,
          selector: 'runningattacks'
        },
        {
          title: "Registered users",
          desc: `Number of users registered on our website`,
          selector: 'users'
        }
      ]
    }

    function switchSlider() {
      if(sliderData.current + 1 >= 3) {
        $(".progress-active").remove()
        sliderData.current = 0
      } else {
        sliderData.current = sliderData.current + 1;
      }

      $(".slider-footer-header").html(sliderData.texts[sliderData.current].title)
      $(".slider-footer-desc").html(sliderData.texts[sliderData.current].desc)
      $(".slider-stats").html(_slider_data[sliderData.texts[sliderData.current].selector])

      $(`.slider-footer-header`).animate({
        opacity: '1'
      }, 250)
      $(".slider-stats").animate({
        opacity: '1'
      }, 250)
      $(`.slider-footer-desc`).animate({
        opacity: '1'
      }, 250, () => {
          
      })
      $(".progress-slider .progress:nth-child(" + (sliderData.current + 1) + ")").html(`<div class="progress-active progress-id-${sliderData.current}"></div>`)
      $(`.progress-id-${sliderData.current}`).animate({
        width: '100%'
      }, 5000, () => {
        $(`.slider-stats`).animate({
          opacity: '0'
        }, 250)
        $(`.slider-footer-header`).animate({
          opacity: '0'
        }, 250)
        $(`.slider-footer-desc`).animate({
          opacity: '0'
        }, 250, () => {
          switchSlider()
        })
      })
    }

    $(document).ready(() => {
      if(_slider_loaded) return;
      _slider_loaded = true

      $(".progress-slider .progress:nth-child(" + (sliderData.current + 1) + ")").html(`<div class="progress-active progress-id-${sliderData.current}"></div>`)
      $.post("/dash/rest/charts/stats", (result) => {
        _slider_data = result

        $(".slider-footer-header").html(sliderData.texts[sliderData.current].title)
        $(".slider-footer-desc").html(sliderData.texts[sliderData.current].desc)
        $(".slider-stats").html(_slider_data[sliderData.texts[sliderData.current].selector])
        
        $(".slider-content").show()

        $(`.progress-id-${sliderData.current}`).animate({
          width: '100%'
        }, 5000, () => {
          $(`.slider-footer-header`).animate({
            opacity: '0'
          }, 250)
          $(`.slider-stats`).animate({
            opacity: '0'
          }, 250)
          $(`.slider-footer-desc`).animate({
            opacity: '0'
          }, 250, () => {
            switchSlider()
          })
        })
      })
    })
  </script>
<?php include '../footer.php'; ?>

