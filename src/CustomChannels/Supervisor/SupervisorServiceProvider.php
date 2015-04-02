<?php 

namespace CustomChannels\Supervisor;

use Illuminate\Support\ServiceProvider;

class SupervisorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot()
	{
		$this->package('customchannels/supervisor');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['supervisorclient'] = $this->app->share(function($app){
			return $SupervisorClient; 
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
