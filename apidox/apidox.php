<?php
// ==================================================================
ini_set('display_errors', 'on');
error_reporting(E_ALL);
// ==================================================================
require_once realpath(dirname(__FILE__) . '/apidox/app/Controller.php');
require_once realpath(dirname(__FILE__) . '/apidox/app/Apidox.php');
use Application\Controller;
use Application\Apidox;
$controller = new Controller();
$apidox = $controller->parse(dirname(__FILE__) . '/api');
// ==================================================================
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=8;IE=9;IE=EDGE" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="Apidox Live Documentation for RESTful APIs. A place where APIs are kept. Apidox provide fast, powerful API documentation test calls, so your developers have more time to code great apps.">
<meta name="keywords" content="apidox, live documentantion, api documentation, restful api, json api" />
<meta name="author" content="Vemovi Desarrolla SL vemovi.com">
<meta name="application-name" content="Apidox apidox.net">
<meta name="generator" content="Apidox apidox.net">

<title><?php echo $apidox->getTitle(); ?></title>

<link rel="stylesheet" href="apidox/libs/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="apidox/layouts/fonts/proxima-nova/proxima-nova.css">
<link rel="stylesheet" href="apidox/layouts/fonts/source-code/source-code.css">
<link rel="stylesheet" href="apidox/layouts/fonts/tisa-pro/tisa-pro.css">
<link rel="stylesheet" href="apidox/layouts/fonts/titillium-text/titillium-text.css">
<link rel="stylesheet" href="apidox/libs/jjsonviewer/css/jjsonviewer.css">
<link rel="stylesheet" href="apidox/layouts/css/styles.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top apidox-nav">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
					<span><?php echo $apidox->getTitle(); ?></span>
				</a>
			</div>
			<a class="apidox-powered hidden-xs" target="_blank" href="https://github.com/jalopezsuarez/apidox">
				<span class="powered">{Apidox} Live Documentation for RESTful APIs </span>
				<span class="credits">Generated with Apidox v2.20161121 Powered &lt;XML/&gt;</span>
			</a>
		</div>
	</nav>

	<div class="container-fluid">

		<div class="row apidox-container">

			<!-- Sidebar -->
			<div class="col-md-2 col-md-2a hidden-sm hidden-xs">
				<div class="anchorific-wrapper col-md-2a">
					<nav class="anchorific "></nav>
				</div>
			</div>
			<!-- /Sidebar -->

			<div class="col-md-10 col-md-10a content">

				<div class="col-md-5 apidox-section-sidebar hidden-sm hidden-xs"></div>

				<!-- Application -->
				<div class="row">
					<div class="col-md-7 clearfix clearfix apidox-section-application">
						<div class="apidox-application">
							<div class="title" data-name="title"><?php echo $apidox->getTitle() ?></div>
							<div class="version">
								<span><?php echo $apidox->getVersion(); ?></span>
							</div>
							<div class="total">
								<span><?php echo $apidox->getCounter() ?></span>
							</div>
						</div>
					</div>
					<div class="col-md-5 pull-right apidox-section-service">
						<div class="apidox-service">
							<div class="title">API Server</div>
							<div class="control">
								<input id="service" name="service" data-name="service" type="text" value="<?php echo $apidox->getScheme() . trim($apidox->getUri(), '/') ?>" data-schema="<?php echo $apidox->getScheme() ?>" data-server="<?php echo trim($apidox->getUri(), '/') ?>">
								<a id="restore" data-name="restore" class="restore glyphicon glyphicon-repeat"> </a>

							</div>
						</div>
					</div>
					<div class="col-md-7 clearfix"></div>
				</div>
				<!-- /Application -->

				<?php foreach ($apidox->getEndpoints() as $endpointResource) : ?>
				<?php $methodCounting = count($endpointResource[Apidox::METHODS])?>
				<?php if ($methodCounting > 0 ): ?>

				<!-- Section -->
				<div class="row apidox-section-header">
					<div class="col-md-7 clearfix apidox-section-endpoint">
						<div class="apidox-endpoint">
							<div class="prefix">
								<h1>
									<?php echo $endpointResource[Apidox::NAME]?>
								</h1>
							</div>
							<div class="total">
								<span><?php echo $endpointResource[Apidox::COUNTER] ?></span>
							</div>
						</div>
					</div>
					<div class="col-md-5 pull-right"></div>
					<div class="col-md-7 clearfix"></div>
				</div>
				<!-- /Section -->

				<?php foreach ($endpointResource[Apidox::METHODS] as $methodResource) : ?>

				<!-- Method -->
				<div class="row apidox-section-method">
					<div class="col-md-7 clearfix apidox-section-params">
						<div class="apidox-method">
							<div class="protocol">
								<span><?php echo $methodResource[Apidox::TYPE] ?></span>
							</div>
							<?php $methodName = trim(trim($methodResource[Apidox::NAME]), '/')?>
							<?php if (!is_null($methodName) && strlen($methodName) > 0) :?>
							<h2 class="<?php echo $methodResource[Apidox::DEPRECATED]?'deprecated':''?>"><?php echo $methodName ?></h2>
							<?php else: ?>
							<h2 class="<?php echo $methodResource[Apidox::DEPRECATED]?'deprecated':''?>"><?php echo $methodResource[Apidox::NAME] ?></h2>
							<?php endif; ?>

						</div>
						<div class="apidox-reference">REFERENCE</div>
						<div class="apidox-description">
							<p><?php echo $methodResource[Apidox::DESCRIPTION] ?></p>
						</div>

						<div class="apidox-reference">HEADERS</div>
						<div class="apidox-parameters table-responsive">
							<table>
								<tbody>
									<?php foreach ($methodResource[Apidox::HEADERS] as $headerResource) : ?>
									<!-- Params -->
									<tr data-name="headers">
										<?php if (strcasecmp($headerResource[Apidox::TYPE], Apidox::TYPE_ENUMERATED) === 0) : ?>
										<td data-name="header" class="field"><?php echo $headerResource[Apidox::NAME] ?></td>
										<td class="value"><select data-name="value">
												<?php foreach ($headerResource[Apidox::TYPE_ENUMERATED] as $option) : ?>
													<option value="<?php echo $option[Apidox::VALUE]?>" <?php echo strcasecmp($option[Apidox::VALUE], $headerResource[Apidox::VALUE])==0?'selected':''?>><?php echo $option[Apidox::VALUE]?></option>
												<?php endforeach ?>
											</select></td>
										<td class="type <?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $headerResource[Apidox::TYPE] ?></td>
										<td class="description">
											<p><?php echo $headerResource[Apidox::DESCRIPTION] ?></p>
											<table class="values">
												<tbody>
														<?php foreach ($headerResource[Apidox::TYPE_ENUMERATED] as $option) : ?>
														<tr>
														<td><?php echo $option[Apidox::VALUE]?></td>
														<td><?php echo $option[Apidox::DESCRIPTION]?></td>
													</tr>
														<?php endforeach ?>
													</tbody>
											</table>
										</td>
											<?php elseif (strcasecmp($headerResource[Apidox::TYPE], Apidox::TYPE_BOOLEAN) === 0) : ?>
											<td data-name="header" class="field"><?php echo $headerResource[Apidox::NAME] ?></td>
										<td class="value"><select data-name="value">
												<option value="1">TRUE</option>
												<option value="0">FALSE</option>
											</select></td>
										<td class="type <?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $headerResource[Apidox::TYPE] ?></td>
										<td class="description">
											<p><?php echo $headerResource[Apidox::DESCRIPTION] ?></p>
										</td>
											<?php elseif (strcasecmp($headerResource[Apidox::TYPE], Apidox::TYPE_FILE) === 0) : ?>
											<td data-name="header" class="field"><?php echo $headerResource[Apidox::NAME] ?></td>
										<td class="value"><div class="apidox-file">
												<span>
													browse&hellip;
													<input type="file" multiple>
												</span>
											</div></td>
										<td class="type <?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $headerResource[Apidox::TYPE] ?></td>
										<td class="description">
											<p><?php echo $headerResource[Apidox::DESCRIPTION] ?></p>
										</td>
											<?php elseif (strcasecmp($headerResource[Apidox::TYPE], Apidox::TYPE_ARRAY) === 0) : ?>
											<td data-name="header" class="field"><?php echo $headerResource[Apidox::NAME] ?></td>
										<td class="value"><input data-name="value" name="<?php echo $headerResource[Apidox::NAME] . '[]' ?>" value="<?php echo $headerResource[Apidox::VALUE] ?>"
												placeholder="<?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>" /></td>
										<td class="type <?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $headerResource[Apidox::TYPE] ?></td>
										<td class="description"><?php echo $headerResource[Apidox::DESCRIPTION] ?></td>
											<?php else :?>
											<td data-name="header" class="field"><?php echo $headerResource[Apidox::NAME] ?></td>
										<td class="value"><input data-name="value" name="<?php echo $headerResource[Apidox::NAME] ?>" value="<?php echo $headerResource[Apidox::VALUE] ?>" placeholder="<?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>" /></td>
										<td class="type <?php echo strcasecmp($headerResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $headerResource[Apidox::TYPE] ?></td>
										<td class="description"><?php echo $headerResource[Apidox::DESCRIPTION] ?></td>
										<?php endif; ?>
									</tr>
									<!-- /Params -->
									<?php endforeach ?>

									</tbody>
							</table>
						</div>

						<div class="apidox-reference">PARAMETERS</div>
						<div class="apidox-parameters table-responsive">
							<table>
								<tbody>
									<?php foreach ($methodResource[Apidox::PARAMS] as $paramsResource) : ?>
									<!-- Params -->
									<tr data-name="params">
										<?php if (strcasecmp($paramsResource[Apidox::TYPE], Apidox::TYPE_ENUMERATED) === 0) : ?>
										<td data-name="param" class="field"><?php echo $paramsResource[Apidox::NAME] ?></td>
										<td class="value"><select data-name="value">
												<?php foreach ($paramsResource[Apidox::TYPE_ENUMERATED] as $option) : ?>
													<option value="<?php echo $option[Apidox::VALUE]?>" <?php echo strcasecmp($option[Apidox::VALUE], $paramsResource[Apidox::VALUE])==0?'selected':''?>><?php echo $option[Apidox::VALUE]?></option>
												<?php endforeach ?>
											</select></td>
										<td class="type <?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $paramsResource[Apidox::TYPE] ?></td>
										<td class="description">
											<p><?php echo $paramsResource[Apidox::DESCRIPTION] ?></p>
											<table class="values">
												<tbody>
														<?php foreach ($paramsResource[Apidox::TYPE_ENUMERATED] as $option) : ?>
														<tr>
														<td><?php echo $option[Apidox::VALUE]?></td>
														<td><?php echo $option[Apidox::DESCRIPTION]?></td>
													</tr>
														<?php endforeach ?>
													</tbody>
											</table>
										</td>
											<?php elseif (strcasecmp($paramsResource[Apidox::TYPE], Apidox::TYPE_BOOLEAN) === 0) : ?>
											<td data-name="param" class="field"><?php echo $paramsResource[Apidox::NAME] ?></td>
										<td class="value"><select data-name="value">
												<option value="1">TRUE</option>
												<option value="0">FALSE</option>
											</select></td>
										<td class="type <?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $paramsResource[Apidox::TYPE] ?></td>
										<td class="description">
											<p><?php echo $paramsResource[Apidox::DESCRIPTION] ?></p>
										</td>
											<?php elseif (strcasecmp($paramsResource[Apidox::TYPE], Apidox::TYPE_FILE) === 0) : ?>
											<td data-name="param" class="field"><?php echo $paramsResource[Apidox::NAME] ?></td>
										<td class="value"><div class="apidox-file">
												<span>
													browse&hellip;
													<input type="file" multiple>
												</span>
											</div></td>
										<td class="type <?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $paramsResource[Apidox::TYPE] ?></td>
										<td class="description">
											<p><?php echo $paramsResource[Apidox::DESCRIPTION] ?></p>
										</td>
											<?php elseif (strcasecmp($paramsResource[Apidox::TYPE], Apidox::TYPE_ARRAY) === 0) : ?>
											<td data-name="param" class="field"><?php echo $paramsResource[Apidox::NAME] ?></td>
										<td class="value"><input data-name="value" name="<?php echo $paramsResource[Apidox::NAME] . '[]' ?>" value="<?php echo $paramsResource[Apidox::VALUE] ?>"
												placeholder="<?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>" /></td>
										<td class="type <?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $paramsResource[Apidox::TYPE] ?></td>
										<td class="description"><?php echo $paramsResource[Apidox::DESCRIPTION] ?></td>
											<?php else :?>
											<td data-name="param" class="field"><?php echo $paramsResource[Apidox::NAME] ?></td>
										<td class="value"><input data-name="value" name="<?php echo $paramsResource[Apidox::NAME] ?>" value="<?php echo $paramsResource[Apidox::VALUE] ?>" placeholder="<?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>" /></td>
										<td class="type <?php echo strcasecmp($paramsResource[Apidox::REQUIRED],'Y') ===0?'required':'' ?>"><?php echo $paramsResource[Apidox::TYPE] ?></td>
										<td class="description"><?php echo $paramsResource[Apidox::DESCRIPTION] ?></td>
										<?php endif; ?>
									</tr>
									<!-- /Params -->
									<?php endforeach ?>

									</tbody>
							</table>
						</div>
						<div class="apidox-try">
							<a id="try" data-name="try">Call Resource</a>
							<img class="loader hidden" data-name="loader" height="21" src="apidox/layouts/img/spinner@2x.gif"></img>
						</div>
					</div>
					<div class="col-md-5 pull-right apidox-section-response">
						<div class="apidox-bread">
							<?php $breads = explode('/', $methodResource[Apidox::URI]); ?>
							<?php if (is_array($breads) && count($breads) > 0): ?>
								<?php $count = 1; ?>
								<?php foreach($breads as $bread) :?>
								<?php if ($count < count($breads)) :?>
								<div class="bread"><?php echo $bread ?></div>
							<div class="separator">/</div>
								<?php else:?>
								<div class="active"><?php echo strlen($bread)>0?$bread:'&nbsp;' ?></div>
								<?php endif;?>
								<?php $count++; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							</div>
						<div class="apidox-entitled">Request</div>
						<div class="apidox-request">
							<span id="type" class="protocol" data-type="<?php echo $methodResource[Apidox::TYPE] ?>"><?php echo $methodResource[Apidox::TYPE] ?></span>
							<a id="link" data-name="link" target="_blank" href="<?php echo $apidox->getScheme()?><?php echo trim($apidox->getUri(), '/')?><?php echo '/' . trim($methodResource[Apidox::URI], '/') ?>">
								<span id="server" class="server" data-name="server" data-server="<?php echo $apidox->getScheme()?><?php echo trim($apidox->getUri(), '/')?>" data-uri="<?php echo '/' . trim($methodResource[Apidox::URI], '/')?>" data-param=""><?php echo $apidox->getScheme()?><?php echo trim($apidox->getUri(), '/')?></span>
							</a>
						</div>
						<div class="apidox-entitled">Response</div>
						<div class="apidox-live">
							<div class="status" data-name="status">200</div>
							<div class="body">BODY</div>
							<div class="json-response" data-name="response"></div>
						</div>
					</div>
					<div class="col-md-7 clearfix apidox-section-information <?php echo 0 === --$methodCounting?'last':'' ?>">
						<?php $activeErrors = 'active'?>
						<?php $activeExample = !isset($methodResource[Apidox::ERRORS])?'active':''?>
						<?php $activeInformation = !isset($methodResource[Apidox::ERRORS]) && !isset($methodResource[Apidox::EXAMPLE])?'active':''?>

						<?php if (isset($methodResource[Apidox::ERRORS]) || isset($methodResource[Apidox::EXAMPLE]) || isset($methodResource[Apidox::INFORMATION])) :?>
						<div class="apidox-content-information">
							<ul class="nav nav-tabs">
								<?php $tagger = preg_replace("/[^ \w]+/", "", strtolower($methodResource[Apidox::URI])); ?>
								<?php if (isset($methodResource[Apidox::ERRORS])) :?>
								<li class="<?php echo $activeErrors ?>">
									<a data-toggle="tab" href="<?php echo '#' . $tagger . 'errors' ; ?>">Errors</a>
								</li>
								<?php endif ?>
								<?php if (isset($methodResource[Apidox::EXAMPLE])) :?>
								<li class="<?php echo $activeExample ?>">
									<a data-toggle="tab" href="<?php echo '#' . $tagger . 'examples' ; ?>">Examples</a>
								</li>
								<?php endif ?>
								<?php if (isset($methodResource[Apidox::INFORMATION])) :?>
								<li class="<?php echo $activeInformation ?>">
									<a data-toggle="tab" href="<?php echo '#' . $tagger . 'information' ; ?>">Information</a>
								</li>
								<?php endif ?>
							</ul>

							<div class="tab-content apidox-tabs">
								<?php if (isset($methodResource[Apidox::ERRORS])) :?>
								<div id="<?php echo $tagger . 'errors' ; ?>" class="tab-pane in <?php echo $activeErrors ?>">

									<?php foreach ($methodResource[Apidox::ERRORS] as $keyCategory => $errorCategory) : ?>
									<div class="error-section"><?php echo $keyCategory ?></div>
									<table class="errortable">
										<tbody>
											<?php foreach ($errorCategory as $errorsResource ) : ?>
											<tr>
												<td class="code"><?php echo $errorsResource[Apidox::CODE]?></td>
												<td class="description"><?php echo $errorsResource[Apidox::DESCRIPTION]?></td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
									<?php endforeach; ?>

								</div>
								<?php endif ?>
								<?php if (isset($methodResource[Apidox::EXAMPLE])) :?>
								<div id="<?php echo $tagger . 'examples' ; ?>" class="tab-pane <?php echo $activeExample ?>">
									<pre class="json-examples" data-name="json-examples">
											<?php echo $methodResource[Apidox::EXAMPLE]?>
										</pre>
								</div>
								<?php endif ?>
								<?php if (isset($methodResource[Apidox::INFORMATION])) :?>
								<div id="<?php echo $tagger . 'information' ; ?>" class="tab-pane <?php echo $activeInformation ?>">
									<div class="apidox-information">
										<?php echo $methodResource[Apidox::INFORMATION]?>
									</div>
								</div>
								<?php endif ?>
							</div>
						</div>
						<?php endif ?>
					</div>
				</div>
				<!-- /Method -->

				<?php endforeach; ?>

				<?php endif; ?>
				<?php endforeach; ?>

				<div class="row">
					<div class="col-md-7 clearfix apidox-section-footer"></div>
					<div class="col-md-5"></div>
					<div class="col-md-7 clearfix"></div>
				</div>

			</div>
		</div>

	</div>

	<script src="apidox/libs/jquery/jquery-1.12.0.min.js"></script>
	<script src="apidox/libs/bootstrap/js/bootstrap.min.js"></script>
	<script src="apidox/libs/anchorific/min/anchorific.min.js"></script>
	<script src="apidox/libs/jjsonviewer/js/jjsonviewer.js"></script>
	<script src="apidox/libs/json-js/json2.js"></script>
	<script src="apidox/libs/cookie/src/jquery.cookie.js"></script>

	<script src="apidox/layouts/scripts/apidox.js"></script>

</body>
</html>
