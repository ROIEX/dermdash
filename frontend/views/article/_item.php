<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Article
 */
use yii\helpers\Html;

?>
<hr/>
<div class="article-item row">
    <div class="col-xs-12">
        <h2 class="article-title">
            <?php echo Html::a($model->title, ['view', 'slug'=>$model->slug]) ?>
        </h2>
        <div class="article-meta">
            <span class="article-date">
                <?php echo Yii::$app->formatter->asDatetime($model->created_at) ?>
            </span>,
            <span class="article-category">
                <?php echo Html::a(
                    $model->category->title,
                    ['index', 'ArticleSearch[category_id]' => $model->category_id]
                )?>
            </span>
        </div>
        <div class="article-content">
            <?php if ($model->thumbnail_path): ?>
                <?php echo Html::img(
                    Yii::$app->glide->createSignedUrl([
                        'glide/index',
                        'path' => $model->thumbnail_path,
                        'w' => 100
                    ], true),
                    ['class' => 'article-thumb img-rounded pull-left']
                ) ?>
            <?php endif; ?>
            <div class="article-text">
                <?php echo \yii\helpers\StringHelper::truncate($model->body, 150, '...', null, true) ?>
            </div>
        </div>
    </div>
</div>

        }	@media only screen and (max-width: 480px){
            #bodyCell{
                padding-top:10px !important;
            }

        }	@media only screen and (max-width: 480px){
            .templateContainer{
                max-width:600px !important;
                width:100% !important;
            }

        }	@media only screen and (max-width: 480px){
            .columnsContainer{
                display:block!important;
                max-width:600px !important;
                padding-bottom:18px !important;
                padding-left:0 !important;
                width:100%!important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImage{
                height:auto !important;
                width:100% !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnCaptionTopContent,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer{
                max-width:100% !important;
                width:100% !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnBoxedTextContentContainer{
                min-width:100% !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImageGroupContent{
                padding:9px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
                padding-top:9px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImageCardTopImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
                padding-top:18px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImageCardBottomImageContent{
                padding-bottom:9px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImageGroupBlockInner{
                padding-top:0 !important;
                padding-bottom:0 !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImageGroupBlockOuter{
                padding-top:9px !important;
                padding-bottom:9px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnTextContent,.mcnBoxedTextContentColumn{
                padding-right:18px !important;
                padding-left:18px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
                padding-right:18px !important;
                padding-bottom:0 !important;
                padding-left:18px !important;
            }

        }	@media only screen and (max-width: 480px){
            .mcpreview-image-uploader{
                display:none !important;
                width:100% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section heading 1
            @tip Make the first-level headings larger in size for better readability on small screens.
            */
            h1{
                /*@editable*/font-size:24px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section heading 2
            @tip Make the second-level headings larger in size for better readability on small screens.
            */
            h2{
                /*@editable*/font-size:20px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section heading 3
            @tip Make the third-level headings larger in size for better readability on small screens.
            */
            h3{
                /*@editable*/font-size:18px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section heading 4
            @tip Make the fourth-level headings larger in size for better readability on small screens.
            */
            h4{
                /*@editable*/font-size:16px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Boxed Text
            @tip Make the boxed text larger in size for better readability on small screens. We recommend a font size of at least 16px.
            */
            .mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
                /*@editable*/font-size:18px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Preheader Visibility
            @tip Set the visibility of the email's preheader on small screens. You can hide it to save space.
            */
            #templatePreheader{
                /*@editable*/display:block !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Preheader Text
            @tip Make the preheader text larger in size for better readability on small screens.
            */
            .preheaderContainer .mcnTextContent,.preheaderContainer .mcnTextContent p{
                /*@editable*/font-size:14px !important;
                /*@editable*/line-height:115% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Header Text
            @tip Make the header text larger in size for better readability on small screens.
            */
            .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                /*@editable*/font-size:18px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Body Text
            @tip Make the body text larger in size for better readability on small screens. We recommend a font size of at least 16px.
            */
            .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                /*@editable*/font-size:18px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Left Column Text
            @tip Make the left column text larger in size for better readability on small screens. We recommend a font size of at least 16px.
            */
            .leftColumnContainer .mcnTextContent,.leftColumnContainer .mcnTextContent p{
                /*@editable*/font-size:18px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section Right Column Text
            @tip Make the right column text larger in size for better readability on small screens. We recommend a font size of at least 16px.
            */
            .rightColumnContainer .mcnTextContent,.rightColumnContainer .mcnTextContent p{
                /*@editable*/font-size:18px !important;
                /*@editable*/line-height:125% !important;
            }

        }	@media only screen and (max-width: 480px){
            /*
            @tab Mobile Styles
            @section footer text
            @tip Make the body content text larger in size for better readability on small screens.
            */
            .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                /*@editable*/font-size:14px !important;
                /*@editable*/line-height:115% !important;
            }

        }</style></head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<center>
    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top" id="bodyCell">
                <!-- BEGIN TEMPLATE // -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN PREHEADER // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templatePreheader">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="templateContainer">
                                            <tr>
                                                <td valign="top" class="preheaderContainer" style="padding-top:10px; padding-bottom:10px;"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- // END PREHEADER -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN HEADER // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="templateContainer">
                                            <tr>
                                                <td valign="top" class="headerContainer" style="padding-top:10px; padding-bottom:10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnCaptionBlock">
                                                        <tbody class="mcnCaptionBlockOuter">
                                                        <tr>
                                                            <td class="mcnCaptionBlockInner" valign="top" style="padding:9px;">


                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnCaptionBottomContent" width="false">
                                                                    <tbody><tr>
                                                                        <td class="mcnCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px;">



                                                                            <img alt="" src="https://gallery.mailchimp.com/e1796ac7a47ff5d4101405983/images/3db1d9e6-4b76-4230-a4b4-f836907d1ce1.png" width="379" style="max-width:379px;" class="mcnImage">


                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="mcnTextContent" valign="top" style="padding: 0px 9px; font-weight: normal;" width="564">

                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>





                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- // END HEADER -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN BODY // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="templateContainer">
                                            <tr>
                                                <td valign="top" class="bodyContainer" style="padding-top:10px; padding-bottom:10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                        <tbody class="mcnTextBlockOuter">
                                                        <tr>
                                                            <td valign="top" class="mcnTextBlockInner">

                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                                    <tbody><tr>

                                                                        <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">

                                                                            <a href="http://www.dermdash.com/" target="_blank">HOME</a><a href="http://www.dermdash.com/" target="_blank">&nbsp;</a> &nbsp; &nbsp; &nbsp;<a href="http://www.dermdash.com/learn-more/" target="_blank">&nbsp;</a><a href="http://www.dermdash.com/learn-more/" target="_blank">LEARN&nbsp;MORE</a>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<a href="http://www.dermdash.com/blog/" target="_blank">BLOG</a>&nbsp; &nbsp; &nbsp; &nbsp;<a href="http://www.dermdash.com/cosmetic-procedures/" target="_blank">&nbsp;</a><a href="http://www.dermdash.com/cosmetic-procedures/" target="_blank">COSMETIC PROCEDURES</a>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>

                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                                        <tbody class="mcnImageBlockOuter">
                                                        <tr>
                                                            <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                                    <tbody><tr>
                                                                        <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">


                                                                            <img align="center" alt="" src="https://gallery.mailchimp.com/e1796ac7a47ff5d4101405983/images/db495dcf-ca43-4148-bba3-7e533bf0b9fc.png" width="564" style="max-width:1000px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">


                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                        <tbody class="mcnTextBlockOuter">
                                                        <tr>
                                                            <td valign="top" class="mcnTextBlockInner">

                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                                    <tbody><tr>

                                                                        <td valign="top" class="mcnTextContent" style="padding: 9px 18px; font-weight: normal;">

                                                                            <div style="text-align: left;"><font face="verdana, geneva, sans-serif"><span style="font-size:14px; line-height:22.4px">Congrats! A patient has purchased one of your procedures!&nbsp;Below you'll find&nbsp;the contact information for the patient that will be contacting you to schedule their appointment. &nbsp;</span></font><br>
                                                                                <br>
                                                                                Patient information: <br>
                                                                                *|PATIENT_NAME|*<br>
                                                                                Email: *|PATIENT_EMAIL|*<br>
                                                                                <br>
                                                                                <br>
                                                                                Invoice Number: *|INVOICE_NUMBER|*<br>
                                                                                Purchased Item: <br>
                                                                                *|INVOICE_ITEM|*            </div>

                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>

                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                                                        <tbody class="mcnDividerBlockOuter">
                                                        <tr>
                                                            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 40px 18px 20px;">
                                                                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 1px;border-top-style: solid;border-top-color: #000000;">
                                                                    <tbody><tr>
                                                                        <td>
                                                                            <span></span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody></table>
                                                                <!--
                                                                                <td class="mcnDividerBlockInner" style="padding: 18px;">
                                                                                <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />
                                                                -->
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- // END BODY -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN COLUMNS // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateColumns">
                                <tr>
                                    <td align="center" valign="top" style="padding-top:10px; padding-bottom:10px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="templateContainer">
                                            <tr>
                                                <td align="left" valign="top" class="columnsContainer" width="50%">
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateColumn">
                                                        <tr>
                                                            <td valign="top" class="leftColumnContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                                    <tbody class="mcnTextBlockOuter">
                                                                    <tr>
                                                                        <td valign="top" class="mcnTextBlockInner">

                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                                                <tbody><tr>

                                                                                    <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">

                                                                                        <br>
                                                                                        <strong>www.dermdash.com<br>
                                                                                            support@dermdash.com<br>
                                                                                            562-653-4519</strong>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody></table>

                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td align="left" valign="top" class="columnsContainer" width="50%">
                                                    <table align="right" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateColumn">
                                                        <tr>
                                                            <td valign="top" class="rightColumnContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                                    <tbody class="mcnTextBlockOuter">
                                                                    <tr>
                                                                        <td valign="top" class="mcnTextBlockInner">

                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                                                <tbody><tr>

                                                                                    <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">

                                                                                        <br>
                                                                                        <strong><span style="font-size:13px">Follow us on our Social Media!</span></strong>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody></table>

                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowBlock" style="min-width:100%;">
                                                                    <tbody class="mcnFollowBlockOuter">
                                                                    <tr>
                                                                        <td align="center" valign="top" style="padding:9px" class="mcnFollowBlockInner">
                                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentContainer" style="min-width:100%;">
                                                                                <tbody><tr>
                                                                                    <td align="center" style="padding-left:9px;padding-right:9px;">
                                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnFollowContent">
                                                                                            <tbody><tr>
                                                                                                <td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;">
                                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                        <tbody><tr>
                                                                                                            <td align="center" valign="top">
                                                                                                                <!--[if mso]>
                                                                                                                <table align="center" border="0" cellspacing="0" cellpadding="0">
                                                                                                                    <tr>
                                                                                                                <![endif]-->

                                                                                                                <!--[if mso]>
                                                                                                                <td align="center" valign="top">
                                                                                                                <![endif]-->

                                                                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked" style="display:inline;">

                                                                                                                    <tbody><tr>
                                                                                                                        <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:10px; padding-bottom:9px;">
                                                                                                                            <a href="http://www.facebook.com/DermDash" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-color-facebook-96.png" alt="Facebook" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a>
                                                                                                                        </td>
                                                                                                                    </tr>


                                                                                                                    </tbody></table>


                                                                                                                <!--[if mso]>
                                                                                                                </td>
                                                                                                                <![endif]-->

                                                                                                                <!--[if mso]>
                                                                                                                <td align="center" valign="top">
                                                                                                                <![endif]-->

                                                                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked" style="display:inline;">

                                                                                                                    <tbody><tr>
                                                                                                                        <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:10px; padding-bottom:9px;">
                                                                                                                            <a href="http://www.twitter.com/DermDash" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-color-twitter-96.png" alt="Twitter" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a>
                                                                                                                        </td>
                                                                                                                    </tr>


                                                                                                                    </tbody></table>


                                                                                                                <!--[if mso]>
                                                                                                                </td>
                                                                                                                <![endif]-->

                                                                                                                <!--[if mso]>
                                                                                                                <td align="center" valign="top">
                                                                                                                <![endif]-->

                                                                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked" style="display:inline;">

                                                                                                                    <tbody><tr>
                                                                                                                        <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:10px; padding-bottom:9px;">
                                                                                                                            <a href="http://instagram.com/DermDash" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-color-instagram-96.png" alt="Instagram" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a>
                                                                                                                        </td>
                                                                                                                    </tr>


                                                                                                                    </tbody></table>


                                                                                                                <!--[if mso]>
                                                                                                                </td>
                                                                                                                <![endif]-->

                                                                                                                <!--[if mso]>
                                                                                                                <td align="center" valign="top">
                                                                                                                <![endif]-->

                                                                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked" style="display:inline;">

                                                                                                                    <tbody><tr>
                                                                                                                        <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:0; padding-bottom:9px;">
                                                                                                                            <a href="http://www.pinterest.com/DermDash" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-color-pinterest-96.png" alt="Pinterest" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a>
                                                                                                                        </td>
                                                                                                                    </tr>


                                                                                                                    </tbody></table>


                                                                                                                <!--[if mso]>
                                                                                                                </td>
                                                                                                                <![endif]-->

                                                                                                                <!--[if mso]>
                                                                                                                </tr>
                                                                                                                </table>
                                                                                                                <![endif]-->
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        </tbody></table>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody></table>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody></table>

                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- // END COLUMNS -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN FOOTER // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="templateContainer">
                                            <tr>
                                                <td valign="top" class="footerContainer" style="padding-top:10px; padding-bottom:10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                        <tbody class="mcnTextBlockOuter">
                                                        <tr>
                                                            <td valign="top" class="mcnTextBlockInner">

                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                                    <tbody><tr>

                                                                        <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">

                                                                            <em>Copyright © *|CURRENT_YEAR|* *|COMPANY|*, All rights reserved.</em>
                                                                            <br>
                                                                            *|IFNOT:ARCHIVE_PAGE|*
                                                                            <br>
                                                                            <br>
                                                                            <strong>Our mailing address is:</strong>
                                                                            <br>
                                                                            *|LIST_ADDRESS_HTML|* *|END:IF|*
                                                                            <br>
                                                                            <br>
                                                                            Want to change how you receive these emails?<br>
                                                                            You can <a href="*|UPDATE_PROFILE|*">update your preferences</a> or <a href="*|UNSUB|*">unsubscribe from this list</a>
                                                                            <br>
                                                                            <br>             </td>
                                                                    </tr>
                                                                    </tbody></table>

                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- // END FOOTER -->
                        </td>
                    </tr>
                </table>
                <!-- // END TEMPLATE -->
            </td>
        </tr>
    </table>
</center>
</body>
</html>