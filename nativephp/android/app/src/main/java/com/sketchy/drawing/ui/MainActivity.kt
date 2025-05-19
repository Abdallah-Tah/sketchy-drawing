package com.sketchy.drawing.ui

import android.content.Intent
import android.content.res.Configuration
import android.os.Build
import android.os.Bundle
import android.os.Looper
import android.os.Handler
import android.util.Log
import android.view.View
import android.webkit.CookieManager
import androidx.annotation.RequiresApi
import androidx.appcompat.app.AppCompatActivity
import com.sketchy.drawing.bridge.PHPBridge
import com.sketchy.drawing.bridge.LaravelEnvironment
import com.sketchy.drawing.databinding.ActivityMainBinding
import com.sketchy.drawing.network.WebViewManager
import android.webkit.WebView
import androidx.activity.addCallback
import com.sketchy.drawing.utils.NativeActionCoordinator
import com.sketchy.drawing.utils.WebViewProvider
import com.sketchy.drawing.security.LaravelCookieStore

class MainActivity : AppCompatActivity(), WebViewProvider {
    private lateinit var binding: ActivityMainBinding
    private val phpBridge = PHPBridge(this)
    private lateinit var laravelEnv: LaravelEnvironment<Any?>
    private lateinit var webViewManager: WebViewManager
    private lateinit var coord: NativeActionCoordinator
    private var pendingDeepLink: String? = null


    @RequiresApi(Build.VERSION_CODES.S)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)
        supportActionBar?.hide()

        binding.splashOverlay.visibility = View.VISIBLE
        LaravelCookieStore.init(applicationContext)
        binding.webView.settings.mediaPlaybackRequiresUserGesture = false

        handleDeepLinkIntent(intent)

        initializeEnvironmentAsync {
            binding.splashOverlay.animate()
                .alpha(0f)
                .setDuration(300)
                .withEndAction {
                    binding.splashOverlay.visibility = View.GONE
                }
                .start()

            webViewManager = WebViewManager(this, binding.webView, phpBridge)
            webViewManager.setup()
            coord = NativeActionCoordinator.install(this)

            val target = pendingDeepLink ?: "/"
            val fullUrl = "http://127.0.0.1$target"
            Log.d("DeepLink", "🚀 Loading final URL: $fullUrl")
            binding.webView.loadUrl(fullUrl)

            pendingDeepLink = null
        }

        onBackPressedDispatcher.addCallback(this) {
            val webView = binding.webView

            if (webView.canGoBack()) {
                webView.goBack()
            } else {
                finish()
            }
        }
    }

     override fun onConfigurationChanged(newConfig: Configuration) {
        super.onConfigurationChanged(newConfig)
        Log.d("MainActivity", "🌀 Config changed: orientation = ${newConfig.orientation}")
    }

    private fun initializeEnvironmentAsync(onReady: () -> Unit) {
        Thread {
            Log.d("LaravelInit", "📦 Starting async Laravel extraction...")
            laravelEnv = LaravelEnvironment(this)
            laravelEnv.initialize()

            Log.d("LaravelInit", "✅ Laravel environment ready — continuing")

            Handler(Looper.getMainLooper()).post {
                onReady()
            }
        }.start()
    }

    override fun onNewIntent(intent: Intent?) {
        super.onNewIntent(intent)
        handleDeepLinkIntent(intent)
    }

    private fun handleDeepLinkIntent(intent: Intent?) {
        val uri = intent?.data ?: return
        Log.d("DeepLink", "🌐 Received deep link: $uri")

        val path = uri.path ?: "/"
        val query = uri.query
        val laravelUrl = buildString {
            append(path)
            if (!query.isNullOrBlank()) {
                append("?")
                append(query)
            }
        }

        Log.d("DeepLink", "📦 Saving deep link for later: $laravelUrl")
        pendingDeepLink = laravelUrl
    }


    private fun initializeEnvironment() {
        clearAllCookies()
        laravelEnv = LaravelEnvironment(this)
        laravelEnv.initialize()

    }

    fun clearAllCookies() {
        val cookieManager = CookieManager.getInstance()
        cookieManager.removeAllCookies(null)
        cookieManager.flush()
        Log.d("CookieInfo", "All cookies cleared")
    }

    override fun onDestroy() {
        super.onDestroy()
        laravelEnv.cleanup()
        phpBridge.shutdown()
    }

    override fun getWebView(): WebView {
        return binding.webView
    }
}