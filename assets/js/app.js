//

function offsetTop($elem) {
  return $elem.getBoundingClientRect().top + window.scrollY;
}

const scrollPoints = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
const $sections = document.querySelectorAll('section');
function section_to_view() {
  const scrollPos = window.scrollY;
  const scrollMax = document.body.offsetHeight - window.innerHeight;

  $sections.forEach($el => {
    const offsetPos = offsetTop($el);
    if(scrollPos >= offsetPos) {
      if(!$el.classList.contains("inview"))
        $el.classList.add("inview");

      if(scrollPos < (offsetPos + $el.offsetHeight)) {
        const percent = ( (scrollPos - offsetPos) / $el.offsetHeight * 100 ).toFixed(0);
        //$el.setAttribute("style", "--scrollPercent: " + percent);
        scrollPoints.forEach(num => {
          if(percent >= num) {
            if(!$el.classList.contains("inview-" + num))
              $el.classList.add("inview-" + num);
          } else if($el.classList.contains("inview-" + num)) {
            $el.classList.remove("inview-" + num);
          }
        });
      } else {
        scrollPoints.forEach(num => {
          if(!$el.classList.contains("inview-" + num ))
            $el.classList.add("inview-" + num);
        });
      }
    } else {
      if($el.classList.contains("inview")) {
        scrollPoints.forEach(num => $el.classList.remove("inview-" + num));
        $el.classList.remove("inview");
      }
    }
  });

  let dataSetSize = Object.keys(dataSet).length;
  //console.log(dataSetSize);
  //console.log(scrollMax);
  let position = Math.round(scrollPos / scrollMax * dataSetSize);
  console.log(position);
  let count = 0;
  let last_known = {'coords': [0, 0], 'weight': 136 };
  let last_known_count = 0;
  let finding_end = false;
  for(let date in dataSet) {
    count++;
    let d = dataSet[date];
    if(!finding_end && d['weight']) {
      last_known = d;
      last_known_count = count;
    }
    if(count == position) {

      if(d['weight']) {
        let x = d['coords'][0];
        let y = d['coords'][1];

        let weight = d['weight'].toFixed(1);
        $graphCircle.setAttribute("style", "left: " + x * 100 + "%; top: " + y*100 + "%;");
        let translateX = Math.round(x*10)/10;
        let translateY = Math.round(y*10)/10;
        $graph.setAttribute("style", "transform: translateX(-" + translateX*100 + "%) translateY(-" + translateY * 100 + "%)");
        $graphCircle.innerHTML = '<span>' + date + " - " + weight + 'kg</span>';
        $graph_yaxis.setAttribute("style", "height: " + $graph.offsetHeight + "px; transform: translateY(-" + translateY * 100 + "%);");
        break;
      } else {
        finding_end = true;
      }
    }
    if(finding_end) {
      if(d['weight']) {
        let length = count - last_known_count;
        let relative_position = position - last_known_count;
        let percent = relative_position / length;
        console.log(percent);
        let x = last_known['coords'][0] + (d['coords'][0] - last_known['coords'][0]) * percent;
        let y = last_known['coords'][1] + (d['coords'][1] - last_known['coords'][1]) * percent;
        let weight = (last_known['weight'] + (d['weight'] - last_known['weight']) * percent).toFixed(1);

        $graphCircle.setAttribute("style", "left: " + x * 100 + "%; top: " + y*100 + "%;");
        let translateX = Math.round(x*10)/10;
        let translateY = Math.round(y*10)/10;
        $graph.setAttribute("style", "transform: translateX(-" + translateX*100 + "%) translateY(-" + translateY * 100 + "%)");
        $graphCircle.innerHTML = '<span>' + weight + 'kg</span>';
        $graph_yaxis.setAttribute("style", "height: " + $graph.offsetHeight + "px; transform: translateY(-" + translateY * 100 + "%);");
        break;
      }
    }

  }
}

var $graphCircle = document.querySelector('.graph__circle');
var $graph = document.querySelector('.graph');
var $graph_yaxis = document.querySelector('.graph__yaxis');

window.addEventListener('scroll', section_to_view);
section_to_view();
