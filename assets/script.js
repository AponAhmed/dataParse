
let processing = false;
let complete = false;
let totalLink = 0;
let leftLink = 0;

function StartParse(e) {
    complete = false;
    $("#resData").html("");
    $(".downloadBtn").hide();
    let btn = $(e.target);
    btn.html('<span class="spinner-grow spinner-grow-sm " role="status" aria-hidden="true"></span> Processing...');
    $.post('index.php', {urlData: $("#UrlInput").val(), method: "StartParse"}, function (response) {
        let resobj = JSON.parse(response);
        if (resobj.done) {
            totalLink = resobj.total;
            leftLink = totalLink;
            startProcess();
        }
    })
}

function startProcess() {
    $('#counter').show();

    setInterval(function () {
        let c = (totalLink - leftLink);
        $("#completeLink").html(c);
        $("#totalLinks").html(totalLink);
        let pp = (100 / totalLink) * c;
        //console.log(totalLink, c, pp);
        $(".prog").css('width', pp + '%');
        if (complete == false) {
            singleExe();
        }
    }, 1000);
}

function singleExe() {
    if (processing === false) {
        processing = true;
        $.post('index.php', {method: "singleExe"}, function (response) {
            processing = false;
            if (response == "complete") {
                complete = true;
                $("#startBtn").html("Start");
                $(".downloadBtn").show();
            } else {
                let resObjectAll = JSON.parse(response);
                leftLink = resObjectAll.left;
                let htm = "<tr>";
                let resObject = resObjectAll.row;
                for (const key in resObject) {
                    htm += "<td title='" + resObject[key] + "'>" + resObject[key] + "</td>";
                }
                htm += "</tr>";
                $("#resData").append(htm);
            }
        });
    }

}


function resetParser(e) {
    $("#resData").html("");
    $("#UrlInput").val("");
    $(".downloadBtn").hide();
    $(".prog").removeAttr('style');
    $("#completeLink").html("0");
    $("#totalLinks").html("0");
    $("#counter").hide();
}