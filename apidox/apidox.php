<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<?php

// Re-ordering endpoints.
function reorderArrayEndpoints($arrayOriginal, &$arrayOrder)
{
	$arrayNew = array();
	$numOrders = count($arrayOrder);

	for ($currIdx = 0; $currIdx < $numOrders; $currIdx++)
	{
		$numOriginal = count($arrayOriginal);
		for ($j = 0; $j < $numOriginal; $j++)
		{
			if (strcmp($arrayOrder[$currIdx], $arrayOriginal[$j]['name']) == 0)
			{
				$arrayNew[] = array_splice($arrayOriginal, $j, 1)[0];
				break;
			}
		}
	}

	if (count($arrayOriginal) > 0)
	{
		$arrayNew = array_merge($arrayNew, $arrayOriginal);
	}

	$arrayOrder = array();
	return $arrayNew;
}

// Re-ordering methods.
function reorderArrayMethods($arrayOriginal, $arrayOrder)
{
	$arrayNew = array();
	$numOrders = count($arrayOrder);

	for ($currIdx = 0; $currIdx < $numOrders; $currIdx++)
	{
		$key = array_search($arrayOrder[$currIdx], $arrayOriginal);
		if ($key === FALSE)
		{
			continue;
		}
		else
		{
			$arrayNew[] = array_splice($arrayOriginal, $key, 1)[0];
		}
	}

	if (count($arrayOriginal) > 0)
	{
		$arrayNew = array_merge($arrayNew, $arrayOriginal);
	}

	return $arrayNew;
}

// Compare function for version ordering.
function cmpVersion($a, $b)
{
	$va = substr($a['name'], 2);
	$vb = substr($b['name'], 2);

	if ($va == $vb)
	{
		return 0;
	}

	return ($a < $b) ? 1 : -1;
}

// Reading main file.
if (file_exists('api/api_config.xml'))
{
	$apiConfig = simplexml_load_file('api/api_config.xml');

	// Title.
	$apiTitle = $apiConfig->attributes()['title'];
	$apiUri = $apiConfig->attributes()['uri'];

	$apiProtocol = "http://";
	if (isset($apiConfig->attributes()['secure']) && $apiConfig->attributes()['secure'] == "Y")
		$apiProtocol = "https://";
}
else
{
	echo 'Configuration file not found.';
}
?>

<title><?php echo $apiTitle; ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<script src="jquery/jquery-1.11/jquery.min.js"></script>
<script src="ajax/index.js"></script>

<body>
	<h1>
		<?php echo $apiTitle; ?>
	</h1>

	<input type="hidden" data-name="uri" value="<?php echo $apiUri ?>">
	<input type="hidden" data-name="protocol"
		value="<?php echo $apiProtocol ?>">

	<?php
	// Endpoints search. Each endpoint is a folder on filesystem under subfolder "api".
	$showDivVersion = true;
	$scan = scandir('api');
	$endpoints = array();
	$versions = array();
	foreach ($scan as $result)
	{
		if ($result === '.' or $result === '..')
		{
			continue;
		}

		$path = 'api/' . $result;
		if (is_dir($path))
		{
			// Version folder name: "v#VERSION_NUMBER"
			if (strlen($result) < 2 || $result[0] !== 'v' || $result[1] !== '#')
			{
				$newEndpoint = array('name' => $result, 'path' => $path);
				$endpoints[] = $newEndpoint;
			}
			else
			{
				$newVersion = array('name' => $result, 'path' => $path);
				$versions[] = $newVersion;
			}
		}
		else if (is_file($path) && strcmp($result, 'index.xml') == 0)
		{
			// Process "index.xml" to order endpoints.
			$orderConfig = simplexml_load_file($path);
			foreach ($orderConfig->order as $orderEndpoint)
			{
				$orderEndpoints[] = (string) $orderEndpoint->attributes()['name'];
			}
		}
	}

	// Version ordering.
	if (count($versions) > 0)
	{
		usort($versions, "cmpVersion");
		$showDivVersion = true;

		// If exists api versions, clean endpoints in this folder.
		$endpoints = array();
		$orderEndpoints = array();
	}
	else
	{
		$fakeVersion = array('name' => '', 'path' => 'api/');
		$versions[] = $fakeVersion;
	}
	?>

	<div id="controls">
		<ul>
			<li><a id="toggle-endpoints" href="javascript:void(0)">Toggle
					All Endpoints</a></li>
			<li><a id="toggle-methods" href="javascript:void(0)">Toggle
					All Methods</a></li>
			<li><span class="total-methods" data-name="total-methods">0</span>
			</li>

			<?php $numVersions = count($versions); ?>
			<?php if ($numVersions > 1 || ($numVersions == 1 && !empty($versions[0]['name']))) :
			?>

			<li class="versions"><span> Version: <?php if ($numVersions > 1) : ?>
			</span> <select id="select-version">
					<?php for ($i = 0; $i < $numVersions; $i++) : ?>
					<option value="<?php echo $versions[$i]['name']; ?>">
						<?php echo substr($versions[$i]['name'], 2); ?>
					</option>
					<?php endfor; ?>
			</select> <?php
						  else :
					  ?> <?php echo substr($versions[0]['name'], 2); ?>
				<?php endif; ?></li>

			<?php endif; ?>
		</ul>
	</div>

	<?php foreach ($versions as $version) : ?>
	<div data-id="frmVersion"
		data-version="<?php echo $version['name']; ?>"
		style="<?php echo $showDivVersion ? "display: block" : "display: none;"
			   ?>">

		<?php
			if (!empty($version['name']))
			{
				$endpoints = array();
				$scan = scandir($version['path']);
				foreach ($scan as $result)
				{
					if ($result === '.' or $result === '..')
					{
						continue;
					}

					$path = $version['path'] . '/' . $result;
					if (is_dir($path))
					{
						$newEndpoint = array('name' => $result, 'path' => $path);
						$endpoints[] = $newEndpoint;
					}
					else if (is_file($path) && strcmp($result, 'index.xml') == 0)
					{
						// Process "index.xml" to order endpoints.
						$orderConfig = simplexml_load_file($path);
						foreach ($orderConfig->order as $orderEndpoint)
						{
							$orderEndpoints[] = (string) $orderEndpoint->attributes()['name'];
						}
					}
				}
			}

			// Endpoints ordering.
			if (count($orderEndpoints) > 0)
			{
				$endpoints = reorderArrayEndpoints($endpoints, $orderEndpoints);
			}
		?>

		<!-- Procesamiento de cada endpoint -->
		<?php foreach ($endpoints as $endpoint) : ?>
		<div id="frmEndPoint">
			<ul>
				<!-- Listado de endpoints. -->
				<li class="endpoint" data-name="endpoint">
					<!-- Cabecera del endpoint. -->
					<h3 class="title">
						<span class="name"> <a href="javascript:void(0)"
							class="endpoint-name"> <?php echo $endpoint['name']; ?>
						</a>
						</span>
						<ul class="actions">
							<li class="list-methods"><a href="javascript:void(0)">Collapse
									Methods</a></li>
							<li class="expand-methods"><a href="javascript:void(0)">Expand
									Methods</a></li>
							<li><span class="count-methods" data-name="count-methods">0</span>
							</li>
						</ul>
					</h3> <?php
								  // Search endpoint methods.
								  $scanMethods = scandir($endpoint['path']);
								  $methods = array();
								  $orderMethods = array();
								  foreach ($scanMethods as $result)
								  {
									  if ($result === '.' or $result === '..')
									  {
										  continue;
									  }
									  $filename = $endpoint['path'] . '/' . $result;
									  if (is_file($filename) && pathinfo($filename, PATHINFO_EXTENSION) == "xml")
									  {
										  if (pathinfo($filename, PATHINFO_FILENAME) != "index")
										  {
											  $methods[] = $filename;
										  }
										  else
										  {
											  // Process "index.xml" to order methods.
											  $orderConfig = simplexml_load_file($filename);
											  foreach ($orderConfig->order as $orderMethod)
											  {
												  $orderMethods[] = $endpoint['path'] . '/' . (string) $orderMethod->attributes()['name'];
											  }
										  }
									  }
								  }
								  // Methods ordering.
								  if (count($orderMethods) > 0)
								  {
									  $methods = reorderArrayMethods($methods, $orderMethods);
								  }
						  ?>
					<ul class="methods hidden">
						<?php foreach ($methods as $method) : ?>
						<?php $methodConfig = simplexml_load_file($method); ?>
						<li
							class="method <?php echo strtolower($methodConfig->attributes()['type']);
										  ?>
						<?php
									if (isset($methodConfig->attributes()['deprecated']) && $methodConfig->attributes()['deprecated'] == 'Y')
									{
										echo 'deprecated';
									}
						?>
						">
							<div class="title clickable">
								<span class="http-method" data-name="http-method"> <?php echo $methodConfig->attributes()['type'];
																				   ?>
								</span> <span class="name"> <?php echo pathinfo($method, PATHINFO_FILENAME);
															?>
								</span> <span class="status-methods"><?php echo $methodConfig->attributes()['status'];
																	 ?></span>
							</div>
							<form class="hidden">
								<span data-name="uri" class="uri"> <?php echo $methodConfig->attributes()['uri'];
																   ?>
								</span> <span class="description"> <?php echo $methodConfig->attributes()['description'];
																   ?>
								</span>

								<?php if (count($methodConfig->param) > 0) : ?>
								<h4>Parameters</h4>
								<table class="parameters">
									<thead>
										<tr>
											<th>Parameter</th>
											<th>Value</th>
											<th>Type</th>
											<th width="100%">Description</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($methodConfig->param as $paramConfig) : ?>
										<tr
											class="<?php if ($paramConfig->attributes()['required'] == "Y")
																		   echo 'required';
												   ?>"
											data-name="item">
											<td class="name" data-name="param"><?php echo $paramConfig->attributes()['name'];
																			   ?></td>
											<td class="parameter"><?php if (strcmp($paramConfig->attributes()['type'], "enumerated") != 0) :
																  ?>
												<input data-name="value"
												value="<?php
																			   if (isset($paramConfig->attributes()['defaultValue']))
																				   echo $paramConfig->attributes()['defaultValue'];
													   ?>"
												placeholder="<?php if ($paramConfig->attributes()['required'] == "Y")
																						 echo 'required';
															 ?>">
												<?php
																	else :
												?> <select
												data-name="select">
													<?php foreach ($paramConfig->option as $option) :
													?>
													<option
														value="<?php echo $option->attributes()['value'];
															   ?>"
														<?php if (isset($option->attributes()['defaultValue']) && $option->attributes()['defaultValue'] == 'Y')
																						echo 'data-default="Y"';
														?>>
														<?php echo $option->attributes()['value'];
														?>
													</option>
													<?php endforeach; ?>
											</select> <?php endif; ?></td>
											<td class="type"><?php echo $paramConfig->attributes()['type'];
															 ?></td>
											<td class="description">
												<p>
													<?php echo $paramConfig->attributes()['description']
													?>
												</p> <?php if (strcmp($paramConfig->attributes()['type'], "enumerated") == 0) :
													 ?>
												<table class="table-enumerated">
													<tbody>
														<?php foreach ($paramConfig->option as $option) :
														?>
														<tr>
															<td class="description"><?php echo $option->attributes()['value'];
																					?></td>
															<td class="description"><?php echo $option->attributes()['description'];
																					?></td>
														</tr>
														<?php endforeach; ?>
													</tbody>
												</table> <?php endif; ?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php
											else :
								?>
								<br>
								<?php endif; ?>

								<div class="tryit-wrapper">
									<input class="tryit-button" data-name="tryit" type="submit"
										id="tryit" value="Run Method" />
									<div class="tryit-loading tryit-oculto"
										data-name="tryit-loading"></div>
								</div>
								<div class="result">

									<div class="tabs-container">
										<ul class="tabs">
											<li class="tab-link current" data-tab="tab-1">Run:</li>
											<?php if (isset($methodConfig->response->success) && strlen(trim($methodConfig->response->success)) > 0) :
											?>
											<li class="tab-link" data-tab="tab-2">Success Sample:</li>
											<?php endif; ?>
											<?php if (isset($methodConfig->response->error) && strlen(trim($methodConfig->response->error)) > 0) :
											?>
											<li class="tab-link" data-tab="tab-3">Error Sample:</li>
											<?php endif; ?>
											<?php if (isset($methodConfig->response->information) && strlen(trim($methodConfig->response->information)) > 0) :
											?>
											<li class="tab-link" data-tab="tab-4">Information:</li>
											<?php endif; ?>
										</ul>
										<div id="tab-1" class="tab-content current">
											<h4>Request</h4>
											<pre class="call"></pre>
											<h4>Response</h4>
											<pre class="response"></pre>
										</div>
										<?php if (isset($methodConfig->response->success) && strlen(trim($methodConfig->response->success)) > 0) :
										?>
										<div id="tab-2" class="tab-content">
											<h4>Success Sample</h4>
											<pre class="sample" data-name="success">
											<?php echo $methodConfig->response->success;
											?>
											</pre>
										</div>
										<?php endif; ?>
										<?php if (isset($methodConfig->response->error) && strlen(trim($methodConfig->response->error)) > 0) :
										?>
										<div id="tab-3" class="tab-content">
											<h4>Error Sample</h4>
											<pre class="sample" data-name="error">
											<?php echo $methodConfig->response->error;
											?>
											</pre>
										</div>
										<?php endif; ?>
										<?php if (isset($methodConfig->response->information) && strlen(trim($methodConfig->response->information)) > 0) :
										?>
										<div id="tab-4" class="tab-content">
											<h4>Information</h4>
											<div class="information" data-name="information">
												<?php echo $methodConfig->response->information;
												?>
											</div>
										</div>
										<?php endif; ?>
									</div>

								</div>
							</form>
						</li>
						<?php endforeach; ?>
					</ul>
				</li>
			</ul>
		</div>
		<?php endforeach; ?>

		<?php
			$errorFile = $version['path'];
			if (!empty($version['name']))
			{
				$errorFile .= '/';
			}
			$errorFile .= 'errorcodes.xml';

			if (file_exists($errorFile))
			{
				$errorsConfig = simplexml_load_file($errorFile);

				$errors = array();
				if (count($errorsConfig->error) > 0)
				{
					$uncategorized = array();

					foreach ($errorsConfig->error as $error)
					{
						$error = array('code' => $error->attributes()['code'], 'description' => $error->attributes()['description']);
						$uncategorized[] = $error;
					}

					$errors['Uncategorized'] = $uncategorized;
				}

				if (count($errorsConfig->category) > 0)
				{
					foreach ($errorsConfig->category as $category)
					{
						if (count($category->error) > 0)
						{
							$categorized = array();

							foreach ($category->error as $error)
							{
								$error = array('code' => $error->attributes()['code'], 'description' => $error->attributes()['description']);
								$categorized[] = $error;
							}

							$categoryName = (string) $category->attributes()['name'];
							$errors[$categoryName] = $categorized;
						}
					}
				}

				if (count($errors) > 0)
				{
		?>

		<div id="frmEndPoint">
			<ul>
				<li class="endpoint errorcodes" data-name="errorcodes">
					<h3 class="title">
						<span class="name"> <a href="javascript:void(0)"
							class="endpoint-name"> error codes </a>
						</span>
					</h3>
					<ul class="methods hidden">

						<div class="tabs-container">
							<ul class="tabs">
								<li class="tab-link current" data-tab="tab-error-table">Information</li>
								<?php if (isset($errorsConfig->sample)) :
								?>
								<li class="tab-link current" data-tab="tab-error-sample">Error
									Sample</li>
								<?php endif; ?>
							</ul>
							<div id="tab-error-table" class="tab-content current">
								<?php foreach ($errors as $key => $value) : ?>
								<h4>
									<?php echo $key; ?>
								</h4>
								<table class="errortable">
									<thead>
										<tr>
											<th>Code</th>
											<th>Description</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($value as $error) : ?>
										<tr>
											<td class="code"><?php echo $error['code']; ?></td>
											<td class="description"><?php echo $error['description'];
																	?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php endforeach; ?>
							</div>

							<div id="tab-error-sample" class="tab-content">
								<h4>Error Sample</h4>
								<pre class="error" data-name="error">
											<?php echo $errorsConfig->sample; ?>
											</pre>
							</div>

						</div>
					</ul>
				</li>
			</ul>
		</div>
		<?php
				}
			}
		?>

	</div>

	<?php $showDivVersion = false; ?>
	<?php endforeach; ?>

	<?php if ($numVersions > 1) : ?>
	<input type="hidden" name="vselect" id="vselect"
		value="<?php echo $versions[0]['name']; ?>" />
	<?php endif; ?>

	<div id="io-footer">
		<div class="f-left">
			&copy; <a href="https://github.com/jalopezsuarez/apidox">APIDox
				on GitHub!</a>
		</div>
		<div class="f-right">
			Powered by <a href="https://github.com/jalopezsuarez/apidox">APIDox</a>
		</div>
	</div>

	<script src="js/json2.js"></script>
	<script src="js/utilities.js"></script>
	<script src="js/livedocs.js"></script>
	<script src="js/docs.js"></script>
	<script src="js/bookmarks.js"></script>

</body>
</html>
