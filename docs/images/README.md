# Documentation images

Screenshots and visual assets used by the README files (EN / ES / PT-PT).

## Conventions

- **Format**: PNG preferred (lossless, transparent backgrounds when relevant); JPG only for photographic skin previews.
- **Width**: 1200 px is the sweet spot — sharp on GitHub and rescales well on mobile.
- **Weight**: keep each file under 300 KB. Compress with [tinypng.com](https://tinypng.com) or [squoosh.app](https://squoosh.app) before committing.
- **Naming**: lowercase, kebab-case, English. Examples below.
- **Reference from README**: use **relative** paths so they work in any clone/mirror:
  ```markdown
  ![Admin templates tab](docs/images/admin-templates-tab.png)
  ```
  Never use absolute `https://github.com/...` URLs — they break on forks.

## Expected files

### Admin UI screenshots (BS3 admin theme)
| File | What it shows | Used in README section |
| ---- | ------------- | ---------------------- |
| `admin-templates-tab.png` | Admin → Sitedown Styles → Templates tab (gallery of 8 thumbnails) | Installation / Templates |
| `admin-copy-editor.png` | Admin → Texts tab (per-language overrides) | Configuration / Texts |
| `admin-guide.png` | Admin → Guide tab (in-admin documentation) | Documentation |
| `admin-preview.png` | Admin → Preview tab (with a skin loaded) | Preview |
| `admin-about.png` | Admin → About tab (author / license / links) | About / Author |
| `admin-config.png` | Admin → Configuration tab (main prefs form) | Configuration |

### Frontend skin previews (BS5 maintenance page)
One per skin — capture at 1200 × 800 px from `/e107_plugins/sitedown_styles/preview.php?style=<key>`:
| File | Skin |
| ---- | ---- |
| `template-agency.png`     | agency |
| `template-business.png`   | business |
| `template-construction.png` | construction |
| `template-creative.png`   | creative |
| `template-gardening.png`  | gardening |
| `template-handyman.png`   | handyman |
| `template-marketing.png`  | marketing |
| `template-restaurant.png` | restaurant |

### Optional hero/banner
| File | Purpose |
| ---- | ------- |
| `hero-collage.png` | A 3×3 or 2×4 collage of all 8 skins, used as the very first image at the top of the README under the badges. Optional but eye-catching. |
| `hero-live.png`    | A single beauty shot of one skin (your favourite) on a real device mockup. Optional alternative to the collage. |
