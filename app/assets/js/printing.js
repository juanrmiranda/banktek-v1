// IMPRESIONES LETTER PAGE
function closePrint() {
  document.body.removeChild(this.__container__);
}
function setPrint() {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}
function printPage(sURL) {
  var oHideFrame = document.createElement("iframe");
  oHideFrame.onload = setPrint;
  oHideFrame.style.position = "fixed";
  oHideFrame.style.right = "0";
  oHideFrame.style.bottom = "0";
  oHideFrame.style.width = "0";
  oHideFrame.style.height = "0";
  oHideFrame.style.border = "0";
  oHideFrame.src = sURL;
  document.body.appendChild(oHideFrame);
}

// IMPRESIONES DE CAJA
function printTicket(url,reimpresion=false) {
  var left = (screen.width - 580) / 2;
  var top = (screen.height - 500) / 4;
  var w = window.open(baseurlAJAX+"Caja/comprobante/"+url+"/"+reimpresion, "", "width=580,height=500,toolbar=no,scrollbars=no,resizable=no,titlebar=no,top="+ top + ', left=' + left);
}