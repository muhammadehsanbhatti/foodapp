


function addHyphen(element) {

    let ele = document.getElementById(element.id);
    ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.
    ele = ele.split('+').join('');    // Remove dash (-) if mistakenly entered.
    ele = ele.split(' ').join('');    // Remove dash (-) if mistakenly entered.

    let finalVal = ele;
    if (ele.length == 1) {
        // finalVal = '+'+ele;
    }
    else if (ele.length == 10) {
        finalVal = ele.replace(/(\d{2})(\d{3})(\d{5})/, "$1-$2-$3");
    }
    else if (ele.length >= 11) {
        finalVal = ele.replace(/(\d{2})(\d{3})(\d{5})/, "$1-$2-$3");
        // finalVal = finalVal.substring(0, 14);//get first 12 chars
    } else {
        finalVal = ele.match(/.{1,3}/g).join('-');
    }
    // console.log(finalVal);
    finalVal = '+' + finalVal;
    document.getElementById(element.id).value = finalVal;
}