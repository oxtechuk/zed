import { lazy, Suspense } from "react";
import { createBrowserRouter } from "react-router-dom";

import RootLayout from "../pages/RootLayout";
import NotFoundPage from "../pages/NotFoundPage";

import HomePageSkeleton from "../components/skeletons/HomePageSkeleton";
import AllCarsPageSkeleton from "../components/skeletons/AllCarsPageSkeleton";
import AboutPageSkeleton from "../components/skeletons/AboutPageSkeleton";
import BlogsPageSkeleton from "../components/skeletons/BlogsPageSkeleton";
import BlogDetailsPageSkeleton from "../components/skeletons/BlogDetailsPageSkeleton";
import ContactPageSkeleton from "../components/skeletons/ContactPageSkeleton";
import OffersPageSkeleton from "../components/skeletons/OffersPageSkeleton";
import CarRequestPageSkeleton from "../components/skeletons/CarRequestPageSkeleton";

const HomePage = lazy(() => import("../pages/HomePage"));
const AllCarsPage = lazy(() => import("../pages/AllCarsPage"));
const CarDetailsPage = lazy(() => import("../pages/CarDetailsPage"));
const ComparePage = lazy(() => import("../pages/ComparePage"));
const OffersPage = lazy(() => import("../pages/OffersPage"));
const AboutPage = lazy(() => import("../pages/AboutPage"));
const BlogsPage = lazy(() => import("../pages/BlogsPage"));
const BlogDetailsPage = lazy(() => import("../pages/BlogDetailsPage"));
const ContactPage = lazy(() => import("../pages/ContactPage"));
const FinanceCalculatorPage = lazy(() => import("../pages/FinanceCalculatorPage"));
const CarRequestPage = lazy(() => import("../pages/CarRequestPage"));
const BrandsPage = lazy(() => import("../pages/BrandsPage"));

function getRouterBasename(): string | undefined {
  if (typeof window !== "undefined") {
    const base = (window as any).__BASE_PATH__;
    if (base && typeof base === "string" && base.trim() !== "") {
      return base.endsWith("/") ? base.slice(0, -1) : base;
    }
    const appUrl = (window as any).__APP_URL__;
    if (appUrl && typeof appUrl === "string") {
      try {
        const path = new URL(appUrl).pathname;
        if (path && path !== "/" && path !== "") {
          return path.endsWith("/") ? path.slice(0, -1) : path;
        }
      } catch {
        // ignore
      }
    }
    const match = window.location.pathname.match(/^(\/[^\/]+\/public)/);
    if (match) {
      return match[1];
    }
  }
  return undefined;
}

export const router = createBrowserRouter([
  {
    path: "/",
    Component: RootLayout,
    errorElement: <NotFoundPage />,
    children: [
      {
        index: true,
        element: (
          <Suspense fallback={<HomePageSkeleton />}>
            <HomePage />
          </Suspense>
        ),
      },
      {
        path: "/cars",
        element: (
          <Suspense fallback={<AllCarsPageSkeleton />}>
            <AllCarsPage />
          </Suspense>
        ),
      },
      {
        path: "/cars/:slug",
        element: (
          <Suspense fallback={<AllCarsPageSkeleton />}>
            <CarDetailsPage />
          </Suspense>
        ),
      },
      {
        path: "/compare",
        element: (
          <Suspense fallback={<HomePageSkeleton />}>
            <ComparePage />
          </Suspense>
        ),
      },
      {
        path: "/offers",
        element: (
          <Suspense fallback={<OffersPageSkeleton />}>
            <OffersPage />
          </Suspense>
        ),
      },
      {
        path: "/about",
        element: (
          <Suspense fallback={<AboutPageSkeleton />}>
            <AboutPage />
          </Suspense>
        ),
      },
      {
        path: "/blog",
        element: (
          <Suspense fallback={<BlogsPageSkeleton />}>
            <BlogsPage />
          </Suspense>
        ),
      },
      {
        path: "/blog/:slug",
        element: (
          <Suspense fallback={<BlogDetailsPageSkeleton />}>
            <BlogDetailsPage />
          </Suspense>
        ),
      },
      {
        path: "/contact",
        element: (
          <Suspense fallback={<ContactPageSkeleton />}>
            <ContactPage />
          </Suspense>
        ),
      },
      {
        path: "/finance-calculator",
        element: (
          <Suspense fallback={<HomePageSkeleton />}>
            <FinanceCalculatorPage />
          </Suspense>
        ),
      },
      {
        path: "/request-car",
        element: (
          <Suspense fallback={<CarRequestPageSkeleton />}>
            <CarRequestPage />
          </Suspense>
        ),
      },
      {
        path: "/brands",
        element: (
          <Suspense fallback={<HomePageSkeleton />}>
            <BrandsPage />
          </Suspense>
        ),
      },
      {
        path: "*",
        Component: NotFoundPage,
      },
    ],
  },
], {
  basename: getRouterBasename(),
});
