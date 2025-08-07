<style type="text/css">
        @charset "UTF-8";

    @font-face {
        font-family: 'TH Sarabun';
        font-style: normal;
        font-weight: normal;
        src: url("{{ public_path('fonts/Sarabun-Regular.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'TH Sarabun';
        font-style: italic;
        font-weight: normal;
        src: url("{{ public_path('fonts/Sarabun-Italic.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'TH Sarabun';
        font-style: normal;
        font-weight: bold;
        src: url("{{ public_path('fonts/Sarabun-Bold.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'TH Sarabun';
        font-style: italic;
        font-weight: bold;
        src: url("{{ public_path('fonts/Sarabun-BoldItalic.ttf') }}") format('truetype');
    }

    body {
        font-family: 'TH Sarabun';
        font-size: 16px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
    }

    b {
        width: 20%;
    }
    img {
        vertical-align: middle;
        border-style: none;
    }

    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    table {
        border-collapse: collapse;
    }

    th {
        text-align: inherit;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
        margin-bottom: 0.5rem;
       /* font-weight: 700;*/
        line-height: 1.2;
    }

    h1,
    .h1 {
        font-size: 28;
    }

    h2,
    .h2 {
        font-size: 26;
    }

    h3,
    .h3 {
        font-size: 24;
    }

    h4,
    .h4 {
        font-size: 22;
    }

    h5,
    .h5 {
        font-size: 20px;
    }

    h6,
    .h6 {
        font-size: 18px;
    }
    p,
    .p {
        font-size: 16px;
    }

    hr {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid #000;
    }

    .row {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }


    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #000;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #000;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #000;
    }

    .table tbody + tbody {
        border-top: 2px solid #000;
    }

    .table-bordered {
        border: 1px solid #000;
    }

    .table-bordered th,
    .table-bordered td {
        font-size: 18px;
        border: 1px solid #000;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-borderless td,
    .table-borderless th,
    .table-borderless thead th,
    .table-borderless tbody + tbody {
        border: 0;
        padding: 0.2rem;
    }
    .table-test {
        table-layout: auto;
        border: 1px solid #000;
        width: 100%;
    }
    .table-test th,
    .table-test td {
        font-size: 12px;
        padding: 0.5rem;
        border: 1px solid #000;
        word-wrap:break-word;
    }

    .table-test thead th,
    .table-test thead td {
        border-bottom-width: 1px;
        word-wrap:break-word;
    }

    .table-border-th {
        table-layout: auto;
        border: 1px solid #000;
        width: 100%;
    }
    .table-border-th th {
        font-size: 18px;
        padding: 0.2rem;
        border: 1px solid #000;
    }
    .table-border-th td {
        font-size: 18px;
        padding: 0.2rem;
        border: 0;
    }

    .table-border-th thead th,
    .table-border-th thead td {
        border-bottom-width: 1px;
    }

    .mt-0 {
        margin-top: 0px;
    }
    .mt-2 {
        margin-top: 0.2rem !important;
    }
    .mt-5 {
        margin-top: 10px;
    }
    .ml-4 {
      margin-left: 1.5rem !important;
    }
    .ml-5 {
        margin-left: 3rem !important;
    }

    .logobox {
        float: left;
        width: 22%;
        height: 100px;
        border-width: 2px 0px 2px 2px;
        border-style: solid;
        border-color: #000;
        padding: 15px 0px 15px 0px;
    }
    .headerbox {
        float: right;
        width: 75%;
        height: 100px;
        border-width: 2px 2px 2px 2px;
        border-style: solid;
        border-color: #000;
        padding: 15px 10px 15px 10px;
        word-wrap: break-word;
    }

    span:before {
        content: "";
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 5px;
    }
    .square:before {
        border: 2px solid #000;
    }
    .noborder-lr {
        border-width: 0px 1px 0px 1px;
        border-style: solid;
        border-color: #000;
    }

    main {
        height: 500px;
        margin: auto;
        width: 500px;
        position: relative;
        resize: vertical;
        overflow: auto;
    }
    .container {
        width: 100%;
    }
    .table-display {
        width: 100%;
        display: table;
    }
    .row-display {
        width: 100%;
        display: table-row;
    }
    .box-display {
        display: table-cell;
        height: 100px;
        border: 1px solid #000;
    }
    .inline {
        display: inline;
    }
    .inline-block {
        display: inline-block;
    }
    .inline-block-2 {
        width: 20%;
        display: inline-block;
    }
    .inline-block-3 {
        width: 30%;
        display: inline-block;
    }
    .inline-block-4 {
        width: 40%;
        display: inline-block;
    }
    .inline-block-5 {
        width: 50%;
        display: inline-block;
    }
    .inline-block-6 {
        width :60%;
        display: inline-block;
    }
    .inline-block-7 {
        width :70%;
        display: inline-block;
    }
    .text-right {
        text-align: right;
        padding-right: 100px;
    }

    .text-left {
        text-align: left !important;
    }

    .text-center {
        text-align: center;
        vertical-align: middle;
    }
    .text-middle {
        text-align: center;
        vertical-align: middle;
        line-height: 20px;
        display: inline-block;
    }
    .square-block {
        width: 20px;
        height: 16px;
        border: 1px solid #000;
        display: inline-block;
    }
    .double-underline {
        padding-bottom: -3px;
        border-bottom: 3px double #000;
        display: inline-block;
    }
    .dashed {
        padding-bottom: -1px;
        width: 100%;
        border-bottom: 1px dashed #000;
        display: inline-block;
    }
    .dotted {
        padding-bottom: -1px;
        margin-bottom: 3px;
        border-bottom: 1px dotted #000;
        display: inline-block;
    }
    .n-mg-b-1 {
        margin-bottom: -10px;
    }
    .n-mg-b-10 {
        margin-bottom: -100px;
    }
    .n-mg-b-15 {
        margin-bottom: -150px;
    }
    .n-mg-b-18 {
        margin-bottom: -180px;
    }
    .n-mg-b-20 {
        margin-bottom: -200px;
    }
    .n-mg-t-1 {
        margin-top: -10px;
    }
    .mg-b-1 {
        margin-bottom: 10px;
    }
    .n-pd-t-1 {
        padding-top: -10px;
    }
    .pd-t-1 {
        padding-top: 10px;
    }
    .pd-l-1 {
        padding-left: 10px;
    }
    .pd-l-4 {
        padding-left: 40px;
    }
    .checkbox {
        padding-bottom: -6px;
    }
    .font-head-bold {
        font-size: 22px;
        font-weight: bolder;
    }
    .font-title-bold {
        font-size: 18px;
        font-weight: bold;
    }

    .border {
        border: 1px solid #000;
    }

    .box-head {
        width: 100%;
        height: 100%;
        text-align: center;
        vertical-align: middle;
    }

    #watermark {
        position: absolute;
        /**
            Set a position in the page for your image
            This should center it vertically
        **/
        bottom:   10cm;
        left:     11cm;

        /** Change image dimensions**/
        width:    8cm;
        height:   8cm;

        /** Your watermark should be behind every content**/
        z-index:  -1000;
    }
    /* width: 100%; height: 100%; text-align: center; valign: middle; */

    u {
        text-decoration: underline;
    }
    strong {
        font-weight: bold;
    }
    .u-double {
        padding-bottom: -3px;
        border-bottom: 3px double #000;
    }
    .text-gray {
        color:#566573;
    }

    .page-break {
        page-break-before: always;
    }
</style>