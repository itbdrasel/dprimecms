<style type="text/css">

  .preview .footer-table, .preview .footer-table td {border:none}
  .preview table{ border:0; }
  .preview table td, .preview table th{ border:1px solid #2c497e; padding: 6px 7px; line-height:22px; font-size:12px;}

  .o-fig{ height:80px; line-height:28px; background:#f7f7f7; padding:5px 15px; border:1px solid #ddd; border-radius:4px}
  .o-fig .amount{ font-size:26px;}

  .body-table th{ background:#2c497e; color:#fff; text-transform:uppercase; letter-spacing:.5px; -webkit-print-color-adjust: exact !important;}
  .body-table tr.item-row td{border:0; border-left: 1px solid #2c497e;  vertical-align: top; font-size:12px;}
  .body-table tr.item-row td:last-child{border-right: 1px solid #2c497e;}


  .preview table .empty-cell{ border:0; border-top:1px solid #000;}
  .preview .bill-to{ width:80%; margin-top:20px}
  .preview .billto-title{text-transform:uppercase; font-weight:bold; color:#888; font-size:17px; margin-bottom:3px}

  .preview .header-table .name{ font-weight:bold; font-size:20px !important; line-height:30px}
  .preview .header-table .com-logo{}
  .preview .header-table .com-logo img{ width:110px; padding-bottom:5px;}
  .preview .header-table .com-details{ width:65%}
  .preview .header-table{ text-align:left; height:110px; vertical-align: top !important;}

  .header-table .meta .inv-title{ display:block; margin:0 0 20px 0; font-size:30px; font-weight:normal; text-transform:uppercase; color:#2c497e;}

  .status-mark{position:absolute; top:0px; left:-1px; width: 0; height: 0; border-top: 90px solid red; border-right: 90px solid transparent;}
  .status-text{ position:absolute; top: -68px; left: 11px; color: white; transform: rotate(-47deg); font-size:12px; font-weight:bold; text-transform:uppercase;}


  .action-bar{ display:none}
  .preview table{ border:0px}
  /*#preview{ margin:30px auto 10px auto; font-family:Arial, Helvetica, sans-serif;}*/

  .preview .header-table{ border-bottom:none; border:none; border-collapse:collapse; margin-bottom:25px;}
  .preview .header-table td{ border:none; font-size:14px}

  .header-table .meta{ line-height:22px; font-size:13px !important; width:40%; padding-left:10px; text-align:right}

  .preview .body-table{ margin:0 auto; border-left:none;  border-collapse:collapse;}

  .body-table .item-row.last{}

  .preview .footer-table{width: 832px; margin: 0;  padding: 0; margin-top: -2px; margin-left: -2px; border:0;}
  .preview .footer-table td{ border:none}

  .preview .footer-comment{ text-align:center; font-size:12px; padding:10px;}
  .preview .date{ font-size:12px; text-align:right;}
  .preview .total-txt{ text-align:right; font-size:14px !important; line-height:30px}
  .preview .total{ font-size:14px !important;}
  .preview .price,.preview .total{ text-align:right !important;}
  .signature{ width:20%; border-top:1px solid #333; padding:5px 0; font-size:13px}
  .left{ float:left}
  .right{ float:right}
  .inword{ text-transform:uppercase}

  .watermark {
    display: inline;
    position: absolute !important;
    opacity: 0.05;
    font-size: 10em;
    width: 100%;
    text-align: center;
    top:50%;
    right:0px;
    text-transform:uppercase;
    transform:rotate(300deg);
    -webkit-transform:rotate(300deg);
  }
  .footer_table td{
    border: 0 !important;
  }
</style>
<body>
<section style="background: #f3f3f3;padding: 15px;">
  <div style="max-width: 660px;margin: 20px auto; padding:20px; background: #fff;">
    <div>
      <div class="preview">
        <p><b>Subject : </b>{!! $subject !!}</p>
        <b>Message : </b>{!! $message !!}
      </div>
    </div>
  </div>
</section>
</body>
