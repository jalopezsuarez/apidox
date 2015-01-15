<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<?php

// Reordenación de endpoints.wwww
function reorderArrayEndpoints($arrayOriginal, &$arrayOrder)
{
	$arrayNew = array ();
	$numOrders = count($arrayOrder);
	
	for($currIdx = 0; $currIdx < $numOrders; $currIdx ++)
	{
		$numOriginal = count($arrayOriginal);
		for($j = 0; $j < $numOriginal; $j ++)
		{
			if (strcmp($arrayOrder [$currIdx], $arrayOriginal [$j] ['name']) == 0)
			{
				$arrayNew [] = array_splice($arrayOriginal, $j, 1)[0];
				break;
			}
		}
	}
	
	if (count($arrayOriginal) > 0)
	{
		$arrayNew = array_merge($arrayNew, $arrayOriginal);
	}
	
	$arrayOrder = array ();
	return $arrayNew;
}

// Reordenación de métodos.
function reorderArrayMethods($arrayOriginal, $arrayOrder)
{
	$arrayNew = array ();
	$numOrders = count($arrayOrder);
	
	for($currIdx = 0; $currIdx < $numOrders; $currIdx ++)
	{
		$key = array_search($arrayOrder [$currIdx], $arrayOriginal);
		if ($key === FALSE)
		{
			continue;
		}
		else
		{
			$arrayNew [] = array_splice($arrayOriginal, $key, 1)[0];
		}
	}
	
	if (count($arrayOriginal) > 0)
	{
		$arrayNew = array_merge($arrayNew, $arrayOriginal);
	}
	
	return $arrayNew;
}

// Función de comparación para ordenación de versiones.
function cmpVersion($a, $b)
{
	$va = substr($a ['name'], 2);
	$vb = substr($b ['name'], 2);
	
	if ($va == $vb)
	{
		return 0;
	}
	
	// Para ordenar de mayor a menor se invierten los signos típicos de esta instrucción.
	return ($a < $b) ? 1 : - 1;
}

// Lectura del fichero de configuración principal.
if (file_exists('api/api_config.xml'))
{
	$apiConfig = simplexml_load_file('api/api_config.xml');
	
	// Título.
	$apiTitle = $apiConfig->attributes()['title'];
	$apiUri = $apiConfig->attributes()['uri'];
}
else
{
	echo 'No se encontró el fichero de configuración.';
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

	<?php
	// Búsqueda de endpoints. Cada endpoint es una carpeta en el sistema de archivos bajo la subcarpeta "api".
	// TODO: explicación versiones.
	$showDivVersion = true;
	$scan = scandir('api');
	$endpoints = array ();
	$versions = array ();
	foreach ( $scan as $result )
	{
		if ($result === '.' or $result === '..')
		{
			continue;
		}
		
		$path = 'api/' . $result;
		if (is_dir($path))
		{
			// Las carpetas para las versiones se nombran con "v#NUMERO"
			if (strlen($result) < 2 || $result [0] !== 'v' || $result [1] !== '#')
			{
				$newEndpoint = array ('name' => $result,'path' => $path );
				$endpoints [] = $newEndpoint;
			}
			else
			{
				$newVersion = array ('name' => $result,'path' => $path );
				$versions [] = $newVersion;
			}
		}
		else if (is_file($path) && strcmp($result, 'index.xml') == 0)
		{
			// Procesamiento de "index.xml" para ordenación de endpoints.
			$orderConfig = simplexml_load_file($path);
			foreach ( $orderConfig->order as $orderEndpoint )
			{
				$orderEndpoints [] = sprintf("%s", $orderEndpoint->attributes()['name']);
			}
		}
	}
	
	// Ordenación de versiones.
	if (count($versions) > 0)
	{
		usort($versions, "cmpVersion");
		$showDivVersion = true;
		
		// Si existen versiones de la api, limpiamos (por precaución) los endpoints de esta carpeta.
		$endpoints = array ();
		$orderEndpoints = array ();
	}
	else
	{
		$fakeVersion = array ('name' => '','path' => 'api/' );
		$versions [] = $fakeVersion;
	}
	?>

	<div id="controls">
		<ul>
			<li>
				<a id="toggle-endpoints" href="javascript:void(0)">Toggle All Endpoints</a>
			</li>
			<li>
				<a id="toggle-methods" href="javascript:void(0)">Toggle All Methods</a>
			</li>
			<li>
				<span class="total-methods" data-name="total-methods">0</span>
			</li>

			<?php $numVersions = count($versions); ?>
			<?php if ($numVersions > 1 || ($numVersions == 1 && ! empty($versions [0] ['name']))) : ?>

			<li class="versions">
				<span>
					Version:
					<?php if ($numVersions > 1) : ?>
				</span>
				<select id="select-version">
					<?php for ($i = 0; $i < $numVersions; $i++) : ?>
					<option value="<?php echo $versions[$i]['name']; ?>">
						<?php echo substr($versions[$i]['name'], 2); ?>
					</option>
					<?php endfor; ?>
				</select> 
				<?php else : ?>
				<?php echo substr($versions [0] ['name'], 2); ?>
				<?php endif; ?>
			</li>

			<?php endif; ?>
		</ul>
	</div>

	<?php foreach ($versions as $version) : ?>
	<div data-id="frmVersion" data-version="<?php echo $version['name']; ?>" style="<?php echo $showDivVersion ? "display: block" : "display: none;"?>">
		
		<?php
		if (! empty($version ['name']))
		{
			$endpoints = array ();
			$scan = scandir($version ['path']);
			foreach ( $scan as $result )
			{
				if ($result === '.' or $result === '..')
				{
					continue;
				}
				
				$path = $version ['path'] . '/' . $result;
				if (is_dir($path))
				{
					$newEndpoint = array ('name' => $result,'path' => $path );
					$endpoints [] = $newEndpoint;
				}
				else if (is_file($path) && strcmp($result, 'index.xml') == 0)
				{
					// Procesamiento de "index.xml" para ordenación de endpoints.
					$orderConfig = simplexml_load_file($path);
					foreach ( $orderConfig->order as $orderEndpoint )
					{
						$orderEndpoints [] = sprintf("%s", $orderEndpoint->attributes()['name']);
					}
				}
			}
		}
		
		// Ordenación de endpoints.
		if (count($orderEndpoints) > 0)
		{
			$endpoints = reorderArrayEndpoints($endpoints, $orderEndpoints);
		}
		?>

		<!-- Procesamiento de cada endpoint -->
		<?php foreach ( $endpoints as $endpoint ) : ?>
		<div id="frmEndPoint">
			<ul>
				<!-- Listado de endpoints. -->
				<li class="endpoint" data-name="endpoint">
					<!-- Cabecera del endpoint. -->
					<h3 class="title">
						<span class="name">
							<a href="javascript:void(0)" class="endpoint-name">
							<?php echo $endpoint['name']; ?>
							</a>
						</span>
						<ul class="actions">
							<li class="list-methods">
								<a href="javascript:void(0)">Collapse Methods</a>
							</li>
							<li class="expand-methods">
								<a href="javascript:void(0)">Expand Methods</a>
							</li>
							<li>
								<span class="count-methods" data-name="count-methods">0</span>
							</li>
						</ul>
					</h3> 
			<?php
			// Búsqueda de métodos del endpoint.
			$scanMethods = scandir($endpoint ['path']);
			$methods = array ();
			$orderMethods = array ();
			foreach ( $scanMethods as $result )
			{
				if ($result === '.' or $result === '..')
				{
					continue;
				}
				$filename = $endpoint ['path'] . '/' . $result;
				if (is_file($filename) && pathinfo($filename, PATHINFO_EXTENSION) == "xml")
				{
					if (pathinfo($filename, PATHINFO_FILENAME) != "index")
					{
						$methods [] = $filename;
					}
					else
					{
						// Procesamiento de "index.xml" para ordenación de métodos.
						$orderConfig = simplexml_load_file($filename);
						foreach ( $orderConfig->order as $orderMethod )
						{
							$orderMethods [] = sprintf("%s/%s.xml", $endpoint ['path'], $orderMethod->attributes()['name']);
						}
					}
				}
			}
			// Ordenación de métodos.
			if (count($orderMethods) > 0)
			{
				$methods = reorderArrayMethods($methods, $orderMethods);
			}
			?>
					<ul class="methods hidden">
						<!-- Procesamiento de cada método. -->
						<?php foreach ( $methods as $method ) :?>
						<?php $methodConfig = simplexml_load_file($method); ?>
						<li
							class="method <?php echo strtolower($methodConfig->attributes()['type']); ?>
						<?php
				if (isset($methodConfig->attributes()['deprecated']) && $methodConfig->attributes()['deprecated'] == 'Y')
				{
					echo 'deprecated';
				}
				?>
						">
							<div class="title clickable">
								<span class="http-method">
									<?php echo $methodConfig->attributes()['type']; ?>
								</span>
								<span class="name">
									<?php echo pathinfo($method, PATHINFO_FILENAME); ?>
								</span>
								<span class="status-methods"><?php echo $methodConfig->attributes()['status']; ?></span>
							</div>
							<form class="hidden">
								<!-- TODO: value="POST" -->
								<span data-name="uri" class="uri">
									<?php echo $methodConfig->attributes()['uri']; ?>
								</span>
								<span class="description">
									<?php echo $methodConfig->attributes()['description']; ?>
								</span>
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
										<!-- Procesamiento de cada parámetro. -->
										<?php foreach ( $methodConfig->param as $paramConfig ) : ?>
										<tr class="<?php if ($paramConfig->attributes()['required'] == "Y") echo 'required'; ?>" data-name="item">
											<td class="name" data-name="param"><?php echo $paramConfig->attributes()['name']; ?></td>
											<td class="parameter">
											<?php if (strcmp($paramConfig->attributes()['type'], "enumerated") != 0) : ?> 
												<input data-name="value" value="<?php
						if (isset($paramConfig->attributes()['defaultValue']))
							echo $paramConfig->attributes()['defaultValue'];
						?>"
													placeholder="<?php if ($paramConfig->attributes()['required'] == "Y") echo 'required'; ?>"> 
											<?php else: ?> 
												<select data-name="select">
													<?php foreach ($paramConfig->option as $option) : ?>
													<option value="<?php echo $option->attributes()['value']; ?>" <?php if (isset($option->attributes()['defaultValue']) && $option->attributes()['defaultValue'] == 'Y') echo 'data-default="Y"'; ?>>
														<?php echo $option->attributes()['value']; ?>
													</option>
													<?php endforeach; ?>
												</select> 
											<?php endif; ?>
											</td>
											<td class="type"><?php echo $paramConfig->attributes()['type']; ?></td>
											<td class="description">
												<p>
													<?php echo $paramConfig->attributes()['description']?>
												</p>
												<?php if (strcmp($paramConfig->attributes()['type'], "enumerated") == 0) : ?>												
												<table class="table-enumerated">
													<tbody>
												<?php foreach ($paramConfig->option as $option) : ?>
												<tr>
															<td class="description"><?php echo $option->attributes()['value']; ?></td>
															<td class="description"><?php echo $option->attributes()['description']; ?></td>
														</tr>
												<?php endforeach; ?>
												</tbody>
												</table>
												<?php endif; ?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<div class="tryit-wrapper">
									<input class="tryit-button" data-name="tryit" type="submit" id="tryit" value="Run Method" />
									<div class="tryit-loading tryit-oculto" data-name="tryit-loading"></div>
								</div>
								<div class="result">

									<div class="tabs-container">
										<ul class="tabs">
											<li class="tab-link current" data-tab="tab-1">Run:</li>
											<?php if (isset($methodConfig->response->success) && strlen(trim($methodConfig->response->success)) > 0) : ?>
												<li class="tab-link" data-tab="tab-2">Success Sample:</li>
											<?php endif; ?>
											<?php if (isset($methodConfig->response->error) && strlen(trim($methodConfig->response->error)) > 0) : ?>											
												<li class="tab-link" data-tab="tab-3">Error Sample:</li>
											<?php endif; ?>											
										</ul>
										<div id="tab-1" class="tab-content current">
											<h4>Request</h4>
											<pre class="call"></pre>
											<h4>Response</h4>
											<pre class="response"></pre>
										</div>
										<?php if (isset($methodConfig->response->success) && strlen(trim($methodConfig->response->success)) > 0) : ?>
										<div id="tab-2" class="tab-content">
											<h4>Success Sample</h4>
											<pre class="sample" data-name="success">
											<?php echo $methodConfig->response->success; ?>
											</pre>
										</div>
										<?php endif; ?>
										<?php if (isset($methodConfig->response->error) && strlen(trim($methodConfig->response->error)) > 0) : ?>
										<div id="tab-3" class="tab-content">
											<h4>Error Sample</h4>
											<pre class="sample" data-name="error">
											<?php echo $methodConfig->response->error; ?>
											</pre>
										</div>
										<?php endif; ?>
									</div>
									<!-- container -->

								</div>
							</form>
						</li>
						<?php endforeach; ?>
					</ul>
				</li>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>

	<?php $showDivVersion = false; ?>
	<?php endforeach; ?>

	<?php if ($numVersions > 1) : ?>
	<input type="hidden" name="vselect" id="vselect" value="<?php echo $versions[0]['name']; ?>" />
	<?php endif; ?>
	
	<div id="io-footer">
		<div class="f-left">
			&copy;
			<a href="https://github.com/jalopezsuarez/apidox">APIDox on GitHub!</a>
		</div>
		<div class="f-right">
			Powered by
			<a href="https://github.com/jalopezsuarez/apidox">APIDox</a>
		</div>
	</div>

	<script src="js/json2.js"></script>
	<script src="js/utilities.js"></script>
	<script src="js/livedocs.js"></script>
	<script src="js/docs.js"></script>
	<script src="js/bookmarks.js"></script>

</body>
</html>
