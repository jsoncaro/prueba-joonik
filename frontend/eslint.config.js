import js from '@eslint/js';
import globals from 'globals';
import reactHooks from 'eslint-plugin-react-hooks';
import react from 'eslint-plugin-react';
import tseslint from 'typescript-eslint';
import prettier from 'eslint-plugin-prettier';
import pluginPrettierRecommended from 'eslint-plugin-prettier/recommended';

export default tseslint.config([
  {
    files: ['**/*.{ts,tsx,js,jsx}'],
    ignores: ['dist'],
    languageOptions: {
      ecmaVersion: 2020,
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.node
      },
      parser: tseslint.parser
    },
    plugins: {
      '@typescript-eslint': tseslint.plugin,
      react,
      'react-hooks': reactHooks,
      prettier
    },
    settings: {
      react: {
        version: 'detect'
      }
    },
    extends: [
      js.configs.recommended,
      ...tseslint.configs.recommended,
      react.configs.recommended,
      reactHooks.configs['recommended-latest'],
      pluginPrettierRecommended
    ],
    rules: {
      'react/react-in-jsx-scope': 'off',
      'no-console': 'warn',
      '@typescript-eslint/explicit-module-boundary-types': 'off',
      'prettier/prettier': [
        'error',
        {
          singleQuote: true,
          semi: true,
          tabWidth: 2,
          trailingComma: 'es5',
          printWidth: 100
        }
      ]
    }
  }
]);
