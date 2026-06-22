# Educational & Non-Commercial License

Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.

This software and associated documentation files (the "Software") are provided for educational and non-commercial purposes only.

Permission is hereby granted, free of charge, to any person obtaining a copy of this Software, to use, copy, modify, and distribute the Software solely for educational and non-commercial purposes, subject to the following conditions:

1. The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
2. Commercial use, resale, sublicensing, or use in commercial products or services is strictly prohibited without prior written permission from the copyright holder.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

## Third-Party Components and Boundaries

This application utilizes an independent microservice architecture. The core application logic and proprietary codebase are governed strictly by the Educational & Non-Commercial License outlined above.

However, this project includes independent third-party software that is governed by its own license:

* **Pedigree Service (`src/pedigree`)**: This module is an independent HTTP service distributed under the **GNU General Public License Version 3 (GPLv3)**. It communicates with the core application at arm's length via an API. The GPLv3 license applies *only* to the code contained within the `src/pedigree` directory and does not extend to or infect the core application governed by this Educational & Non-Commercial License.
